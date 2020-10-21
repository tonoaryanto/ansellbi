<?php defined('BASEPATH') OR exit('No direct script access allowed');

class History_house extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('grafik_model');
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'History House',
            'head1'     => 'History House',
            'link1'     => 'history_house',
            'isi'       => 'history_house/list',
            'cssadd'    => 'history_house/cssadd',
            'jsadd'     => 'history_house/jsadd',
            'farm'      => $this->umum_model->get('data_kandang',"kode_perusahaan = '".$id_user."'")->result(),
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function rdata(){
        $id_user   = $this->session->userdata('id_user');
        $farm = $this->umum_model->get('data_kandang',"kode_perusahaan = '".$id_user."'");

        $countfarm = $farm->num_rows();
        $datafarm = $farm->result();

        if($countfarm > 0){
            $data = [
                'status' => true,
                'countfarm' => $countfarm
            ];

            $data2 =[];
            $nomor = 0;
            foreach ($datafarm as $value) {
                $isi = $this->umum_model->get('data_realtime',['kode_perusahaan' => $id_user,'kode_kandang' => $value->id])->row_array();
                if($isi['id'] != ''){
                    $tanggal = date_format(date_create($isi['date_create']), "d-m-Y");
                    $jam = date_format(date_create($isi['date_create']), "H:i:s");    
                    $data2[$nomor] = [
                        'id' => $isi['id'],
                        'periode' => $isi['periode'],
                        'growday' => $isi['growday'],
                        'tanggal' => $tanggal,
                        'jam' => $jam,
                        'req_temp' => $isi['req_temp'],
                        'avg_temp' => $isi['avg_temp'],
                        'humidity' => $isi['humidity'],
                        'windspeed' => $isi['windspeed'],
                        'feed' => $isi['feed'],
                        'water' => $isi['water'],
                        'static_pressure' => $isi['static_pressure'],
                        'fan' => $isi['fan']
                    ];
                }else{
                    $tanggal = "-";
                    $jam = "-";    
                    $data2[$nomor] = [
                        'id' => '',
                        'periode' => '0',
                        'growday' => '0',
                        'tanggal' => '',
                        'jam' => '',
                        'req_temp' => '0',
                        'avg_temp' => '0',
                        'humidity' => '0',
                        'windspeed' => '0',
                        'feed' => '0',
                        'water' => '0',
                        'static_pressure' => '0',
                        'fan' => '0'
                    ];
                }
                $nomor = $nomor + 1;
            }

            $data['isi'] = $data2;
        }else{
            $data = [
                'status' => false
            ];
        }
        echo json_encode($data);
    }

    public function farm($idfarm,$sensor=null){
        if ($sensor == null) {redirect('history_house/farm/'.$idfarm.'/temperature');}
        if ($sensor != 'temperature' AND $sensor != 'humidity' AND $sensor != 'wind' AND $sensor != 'feed' AND $sensor != 'water' AND $sensor != 'pressure' AND $sensor != 'fan') {redirect('history_house/farm/'.$idfarm.'/temperature');}
        $this->konfigurasi->cek_url();
        $this->session->set_userdata(['idfarm'=>$idfarm]);
        $id_user   = $this->session->userdata('id_user');

        if($sensor == 'temperature'){$urljs = 'history_house-farm-temperaturejs.js';}
        if($sensor == 'humidity'){$urljs = 'history_house-farm-humidityjs.js';}
        if($sensor == 'wind'){$urljs = 'history_house-farm-windjs.js';}
        if($sensor == 'feed'){$urljs = 'history_house-farm-feedjs.js';}
        if($sensor == 'water'){$urljs = 'history_house-farm-waterjs.js';}
        if($sensor == 'pressure'){$urljs = 'history_house-farm-pressurejs.js';}
        if($sensor == 'fan'){$urljs = 'history_house-farm-fanjs.js';}

        $inidatafarm = $this->umum_model->get('data_kandang',"id = '".$idfarm."' AND kode_perusahaan = '".$id_user."'")->row_array();
        $iniperiode = $this->umum_model->get("(SELECT periode,growday FROM data_record WHERE kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC,growday DESC LIMIT 1) as data")->row_array();
        if ($inidatafarm['nama_kandang'] == '') {echo 'Silent is gold';return;}

        if($iniperiode['periode'] != ''){
            $setperiode = $iniperiode['periode'];
        }else{
            $setperiode = '1';
        }

        if($iniperiode['periode'] != ''){
            $setgrow = $iniperiode['growday'];
        }else{
            $setgrow = '1';
        }

        $data = [
            'txthead1'     => 'History House - '.$inidatafarm['nama_kandang'],
            'head1'     => 'History House',
            'link1'     => '#',
            'head2'     => '<b>'.$inidatafarm['nama_kandang'].'</b>',
            'link2'     => 'history_house/farm/'.$idfarm,
            'isi'       => 'history_house/farm/list',
            'cssadd'    => 'history_house/farm/cssadd',
            'jsadd'     => 'history_house/farm/jsadd',
            'idfarm'    => $idfarm,
            'iniperiode' => $setperiode,
            'inigrow' => $setgrow,
            'urljs' => $urljs
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function grafik_temperature(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');

        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');
        $periode = $this->input->post('periode');

        if ($radio = 'grow') {
            if($growval == $growval2){
                $esqlgrow = "AND growday= '".$growval."' ";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
            }

        }

        $esqlperiode = "AND periode = '".$periode."' ";

        $reqdata['id_user'] = $id_user;
        $reqdata['inidata'] = $inidata;
        $reqdata['id_farm'] = $id_farm;
        $reqdata['esqlperiode'] = $esqlperiode;
        $reqdata['esqlgrow'] = $esqlgrow;
        $reqdata['growval'] = $growval;
        $reqdata['growval2'] = $growval2;

        $hasildata = $this->grafik_model->grafik_temperature($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel]);
    }

    public function grafik_one(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');

        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');
        $periode = $this->input->post('periode');

        if ($radio = 'grow') {
            if($growval == $growval2){
                $esqlgrow = "AND growday= '".$growval."' ";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
            }

        }

        $esqlperiode = "AND periode = '".$periode."' ";

        $reqdata['id_user'] = $id_user;
        $reqdata['inidata'] = $inidata;
        $reqdata['id_farm'] = $id_farm;
        $reqdata['esqlperiode'] = $esqlperiode;
        $reqdata['esqlgrow'] = $esqlgrow;
        $reqdata['growval'] = $growval;
        $reqdata['growval2'] = $growval2;

        $hasildata = $this->grafik_model->grafik_satudata($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel]);
    }

    public function grafikwp(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');

        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');
        $periode = $this->input->post('periode');

        if ($radio = 'grow') {
            if($growval == $growval2){
                $esqlgrow = "AND growday= '".$growval."' ";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
            }

        }

        $esqlperiode = "AND periode = '".$periode."' ";

        $reqdata['id_user'] = $id_user;
        $reqdata['inidata'] = $inidata;
        $reqdata['id_farm'] = $id_farm;
        $reqdata['esqlperiode'] = $esqlperiode;
        $reqdata['esqlgrow'] = $esqlgrow;
        $reqdata['growval'] = $growval;
        $reqdata['growval2'] = $growval2;
        
        $hasildata = $this->grafik_model->grafik_windspeed($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel]);
    }

    public function data_select(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            // $id_user = $this->session->userdata('id_user');
            // $fil1 = $this->input->post('value1');
            // $fil2 = $this->input->post('value2');
            // $fil3 = $this->input->post('value3');

            // $this->db->select('kode_data AS id,nama_data AS text');
            // $this->db->from('kode_data');
            // $this->db->where(['aktif'=>'y']);
            // $this->db->where("kategori_waktu IN ('2','3')");
            // $this->db->order_by('urutan','ASC');
            // $data1 = $this->db->get()->result();

            // $dataini1 = [array('id'   => '','text' => '',)];
            // foreach ($data1 as $data1) {
            //     $dataini = [
            //         'id'   => $data1->id,
            //         'text' => $data1->text,
            //     ];
            //     $dataini1[] = $dataini;
            // }

            $idlabel = [
                'avg_temp',
                'temp_1',
                'temp_2',
                'temp_3',
                'temp_4',
                'temp_out',
                'humidity',
                'feed',
                'water',
                'static_pressure',
                'fan',
                'windspeed',
                'min_windspeed',
                'max_windspeed'
            ];  

            $textlabel = [
                'Average Temperature',
                'Temperature 1',
                'Temperature 2',
                'Temperature 3',
                'Temperature 4',
                'Out Temperature',
                'Humidity',
                'Feed Consumtion Kg',
                'Water Consumtion Liter',
                'Static Pressure',
                'Fan Speed',
                'Wind Speed',
                'Min Wind Speed',
                'Max Wind Speed'    
            ];  

            for ($i=0; $i < count($idlabel); $i++) { 
                $dataini = [
                    'id'   => $idlabel[$i],
                    'text' => $textlabel[$i],
                ];
                $dataini1[] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }

    public function data_select_kandang(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');

            $this->db->select('id,nama_kandang AS text');
            $this->db->from('data_kandang');
            $this->db->where([
                'kode_perusahaan'=>$id_user,
            ]);
            $this->db->order_by('id','ASC');
            $data1 = $this->db->get()->result();

            $dataini1 = [array('id'   => '','text' => '',)];
            foreach ($data1 as $data1) {
                $dataini = [
                    'id'   => $data1->id,
                    'text' => $data1->text,
                ];
                $dataini1[] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }

    public function datatabel(){
        $fil1 = $this->input->post('value1');
        $fil2 = $this->input->post('value2');
        $fil3 = $this->input->post('value3');
        $id_user   = $this->session->userdata('id_user');
        $fildari   = $this->input->post('value4');
        $filsampai = $this->input->post('value5');
        $filhour = $this->input->post('value6');
        $filperiode = $this->input->post('value7');

        if($fil1 == 'DAY_1'){
            if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
                $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
                $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }
        }
        if($fil1 == 'HOUR_1'){
            if($filhour == '-1'){
                $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }
        }

        header('Content-Type: application/json');
        if ($fil1 == 'DAY_1') {echo $this->grafik_model->json_day($fil1,$fil2,$fil3,$id_user,$fildari,$filsampai,$filperiode);}
        if ($fil1 == 'HOUR_1') {echo $this->grafik_model->json_hour($fil1,$fil2,$fil3,$id_user,$filhour,$filperiode);}
    }

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');

        if ($radio = 'grow') {
            $growval = $this->input->post('growval');
            $growval2 = $this->input->post('growval2');
            $periode = $this->input->post('periode');
            $esqlperiode = "AND periode = '".$periode."' ";
            if($growval == $growval2){
                $esqlgrow = "AND growday = '".$growval."' ";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
            }
        }

        $adata = [];
        if($this->input->post('kateg') == 'temp'){$adata = $this->tabeltemperature($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'hum'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'wind'){$adata = $this->tabelwind($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata[0][0],$inidata[0][1],$inidata[0][2]);}
        if($this->input->post('kateg') == 'feed'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'water'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'press'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'fan'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}

        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

    private function tabeltemperature($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {

        $datsql1  = "SELECT id,growday, date_record";
        $datsql1 .= ",req_temp,".$inidata[1].",".$inidata[2].",".$inidata[3].",".$inidata[4].",".$inidata[5].",".$inidata[0];
        $datsql1 .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata['req_temp'];
            $kolomdata[5]  = $isidata[$inidata[1]];
            $kolomdata[6]  = $isidata[$inidata[2]];
            $kolomdata[7]  = $isidata[$inidata[3]];
            $kolomdata[8]  = $isidata[$inidata[4]];
            $kolomdata[9]  = $isidata[$inidata[0]];
            $kolomdata[10] = $isidata[$inidata[5]];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {
        $datsql1  = "SELECT id,growday, date_record,";
        $datsql1 .= $inidata[0];
        $datsql1 .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);
        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata[$inidata[0]];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelwind($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata1,$inidata2,$inidata3)
    {

        $datsql1  = "SELECT id,growday, date_record,";
        $datsql1 .= $inidata1.",".$inidata2.",".$inidata3;
        $datsql1 .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata[$inidata1];
            $kolomdata[5]  = $isidata[$inidata2];
            $kolomdata[6]  = $isidata[$inidata3];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

}
