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

    public function index2(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'History House',
            'head1'     => 'History House',
            'link1'     => 'history_house',
            'isi'       => 'history_house/list2',
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
            $suhuatas = 1;
            $suhubawah = -2;
            foreach ($datafarm as $value) {
                $isi = $this->umum_model->get('data_realtime',['kode_perusahaan' => $id_user,'kode_kandang' => $value->id])->row_array();
                if($isi['id'] != ''){
                    $tanggalsc = date_format(date_create($isi['date_create']), "d-m");
                    $tanggal = date_format(date_create($isi['date_create']), "d F Y");
                    $xmenit = (int)str_split(date_format(date_create($isi['date_create']), "i"))[1] - 5;
                    if($xmenit < 0){
                      $xmenit = 0;
                    }else if($xmenit >= 0){
                      $xmenit = 5;
                    }
                    $menit = str_split(date_format(date_create($isi['date_create']), "i"))[0].$xmenit;
                    $jam = date_format(date_create($isi['date_create']), "H").":".$menit."";
                    $data2['periode'][$nomor] = $isi['periode'];
                    $data2['growday'][$nomor] = $isi['growday'];
                    $data2['tanggal'][$nomor] = '<a href="javascript:void(0);" title="'.$tanggal.'" style="color:#333;">'.$tanggalsc.'</a>';
                    $data2['jam'][$nomor] = $jam;
                    $data2['req_temp'][$nomor] = $isi['req_temp'];

                    $diffsuhu = floatval($isi['avg_temp']) - floatval($isi['req_temp']);
                    if($diffsuhu > $suhuatas){
                        $avgtemp = '#ce3232bf';
                    }else if($diffsuhu < $suhubawah){
                        $avgtemp = '#258fffbf';
                    }else{
                        $avgtemp = '#0000';
                    }
                    $data2['avg_bg'][$nomor] = $avgtemp;
                    $data2['avg_temp'][$nomor] = $isi['avg_temp'];
                    $data2['humidity'][$nomor] = $isi['humidity'];
                    $data2['windspeed'][$nomor] = $isi['windspeed'];
                    $data2['feed'][$nomor] = $isi['feed'];
                    $data2['water'][$nomor] = $isi['water'];
                    $data2['static_pressure'][$nomor] = $isi['static_pressure'];
                    $data2['fan'][$nomor] = $isi['fan'];
                }else{
                    $tanggal = "-";
                    $jam = "-";
                    $data2['periode'][$nomor] = "-";
                    $data2['growday'][$nomor] = "-";
                    $data2['tanggal'][$nomor] = $tanggal;
                    $data2['jam'][$nomor] = $jam;
                    $data2['req_temp'][$nomor] = "-";
                    $data2['avg_temp'][$nomor] = "-";
                    $data2['humidity'][$nomor] = "-";
                    $data2['windspeed'][$nomor] = "-";
                    $data2['feed'][$nomor] = "-";
                    $data2['water'][$nomor] = "-";
                    $data2['static_pressure'][$nomor] = "-";
                    $data2['fan'][$nomor] = "-";
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

    // public function rdata(){
    //     $id_user   = $this->session->userdata('id_user');
    //     $farm = $this->umum_model->get('data_kandang',"kode_perusahaan = '".$id_user."'");

    //     $countfarm = $farm->num_rows();
    //     $datafarm = $farm->result();

    //     if($countfarm > 0){
    //         $data = [
    //             'status' => true,
    //             'countfarm' => $countfarm
    //         ];

    //         $data2 =[];
    //         $nomor = 0;
    //         foreach ($datafarm as $value) {
    //             $isi = $this->umum_model->get('data_realtime',['kode_perusahaan' => $id_user,'kode_kandang' => $value->id])->row_array();
    //             if($isi['id'] != ''){
    //                 $tanggal = date_format(date_create($isi['date_create']), "d-m-Y");
    //                 $xmenit = (int)str_split(date_format(date_create($isi['date_create']), "i"))[1] - 5;
    //                 if($xmenit < 0){
    //                   $xmenit = 0;
    //                 }else if($xmenit >= 0){
    //                   $xmenit = 5;
    //                 }
    //                 $menit = str_split(date_format(date_create($isi['date_create']), "i"))[0].$xmenit;
    //                 $jam = date_format(date_create($isi['date_create']), "H").":".$menit.":00";
    //                 $data2[$nomor] = [
    //                     'id' => $isi['id'],
    //                     'periode' => $isi['periode'],
    //                     'growday' => $isi['growday'],
    //                     'tanggal' => $tanggal,
    //                     'jam' => $jam,
    //                     'req_temp' => $isi['req_temp'],
    //                     'avg_temp' => $isi['avg_temp'],
    //                     'humidity' => $isi['humidity'],
    //                     'windspeed' => $isi['windspeed'],
    //                     'feed' => $isi['feed'],
    //                     'water' => $isi['water'],
    //                     'static_pressure' => $isi['static_pressure'],
    //                     'fan' => $isi['fan']
    //                 ];
    //             }else{
    //                 $tanggal = "-";
    //                 $jam = "-";    
    //                 $data2[$nomor] = [
    //                     'id' => '',
    //                     'periode' => '0',
    //                     'growday' => '0',
    //                     'tanggal' => '',
    //                     'jam' => '',
    //                     'req_temp' => '0',
    //                     'avg_temp' => '0',
    //                     'humidity' => '0',
    //                     'windspeed' => '0',
    //                     'feed' => '0',
    //                     'water' => '0',
    //                     'static_pressure' => '0',
    //                     'fan' => '0'
    //                 ];
    //             }
    //             $nomor = $nomor + 1;
    //         }

    //         $data['isi'] = $data2;
    //     }else{
    //         $data = [
    //             'status' => false
    //         ];
    //     }
    //     echo json_encode($data);
    // }

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
        $iniperiode = $this->umum_model->get("(SELECT periode,growday,date_record,reset_time FROM data_record WHERE keterangan = 'ok' AND kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY date_record DESC LIMIT 1) as data")->row_array();
        if ($inidatafarm['nama_kandang'] == '') {echo 'Silent is gold';return;}

        $hgrowchange = $this->umum_model->get("(SELECT periode,growday FROM data_record WHERE keterangan = 'growchange' AND kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC,growday DESC LIMIT 1) as data")->num_rows();

        if($iniperiode['periode'] != ''){
            $setperiode = $iniperiode['periode'];
        }else{
            $setperiode = '1';
        }

        if($iniperiode['growday'] != ''){
            $setgrow = $iniperiode['growday'];
        }else{
            $setgrow = '1';
        }

        $Wherefortgl = "periode = '".$setperiode."' AND growday = '".$setgrow."' AND keterangan = 'ok' AND kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."'";
        $settgl1 = $this->db->query("SELECT date_record FROM data_record WHERE ".$Wherefortgl." ORDER BY date_record ASC LIMIT 1")->row_array()['date_record'];
        $settgl2 = $this->db->query("SELECT date_record FROM data_record WHERE ".$Wherefortgl." ORDER BY date_record DESC LIMIT 1")->row_array()['date_record'];

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
            'initgl1' => $settgl1,
            'initgl2' => $settgl2,
            'urljs' => $urljs,
            'cekgrowchange' => $hgrowchange
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function changetgl(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->session->userdata('idfarm');
        $periode = $this->input->post('periode');
        $startgl = $this->input->post('tgl');
        $startime = $this->input->post('time').":00";
        $urut = $this->input->post('dt');

        $diff2 = date_format(date_create($startgl." ".$startime),"Y-m-d H:i:s");
        $where = "periode = '".$periode."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok'";
        if($urut == 1){
            $house = $this->db->query("SELECT growday,date_record,reset_time FROM data_record WHERE date_record >= '".$diff2."' AND ".$where." ORDER BY date_record ASC LIMIT 1")->row_array();
        }else if($urut == 2){
            $house = $this->db->query("SELECT growday,date_record,reset_time FROM data_record WHERE date_record <= '".$diff2."' AND ".$where."  ORDER BY date_record DESC LIMIT 1")->row_array();
        }
        $date_in = date_create(date_format(date_create($house['date_record']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,date_create($diff2));
        $growset = (int)$house['growday'] + (int)$difftgl1->format("%R%a");
        $timeset = date_format(date_create($house['date_record']),"H:i");

        if($house['growday'] != ''){
            echo json_encode(['status' => true, 'dataset' => $growset,'timeset' => $timeset]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function changegrow(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->session->userdata('idfarm');
        $periode = $this->input->post('periode');
        $grow1 = $this->input->post('grow');
        $urut = $this->input->post('dt');

        $house2 = $this->db->query("SELECT growday FROM data_record WHERE periode = '".$periode."' AND growday <= '".$grow1."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok' ORDER BY date_record DESC LIMIT 1")->row_array();

        $where = "periode = '".$periode."' AND growday = '".$house2['growday']."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok'";
        if($urut == 1){
            $house = $this->db->query("SELECT growday,date_record FROM data_record WHERE ".$where." ORDER BY date_record ASC LIMIT 1")->row_array();
        }else if($urut == 2){
            $house = $this->db->query("SELECT growday,date_record FROM data_record WHERE ".$where."  ORDER BY date_record DESC LIMIT 1")->row_array();
        }

        $diffgrow = (int)$grow1 - (int)$house['growday'];

        $date=date_create($house['date_record']);
        date_modify($date,$diffgrow." days");
        $tglset = date_format($date,"Y-m-d");
        $timeset = date_format(date_create($house['date_record']),"H:i");

        if($house['date_record'] != ''){
            echo json_encode(['status' => true, 'dataset' => $tglset,'timeset'=>$timeset]);
        }else{
            echo json_encode(['status' => false]);
        }
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
        $tgl = $this->input->post('tgl');
        $tgl2 = $this->input->post('tgl2');
        $time = $this->input->post('time');
        $time2 = $this->input->post('time2');
        $periode = $this->input->post('periode');

        if ($radio == 'grow') {
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
        $reqdata['tgl'] = $tgl;
        $reqdata['tgl2'] = $tgl2;
        $reqdata['time'] = $time;
        $reqdata['time2'] = $time2;

        $hasildata = $this->grafik_model->grafik_temperature($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];
        $difgrow = $growval2 - $growval;

        $sizeyaxis1 = $hasildata['sizeyaxis'];

        echo json_encode([
            'status'    => true,
            'labelgf'   => $isigrowday1,
            'data'      => $isidatagrafik,
            'glabel'    => $glabel,
            'hourdari'  => $growval,
            'linelabel' => $linelabel,
            'difgrow'   => $difgrow,
            'sizeyaxis1' => $sizeyaxis1
            ]);
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
        $tgl = $this->input->post('tgl');
        $tgl2 = $this->input->post('tgl2');
        $time = $this->input->post('time');
        $time2 = $this->input->post('time2');
        $periode = $this->input->post('periode');
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
        $reqdata['tgl'] = $tgl;
        $reqdata['tgl2'] = $tgl2;
        $reqdata['time'] = $time;
        $reqdata['time2'] = $time2;
        $reqdata['growval'] = $growval;
        $reqdata['growval2'] = $growval2;

        $hasildata = $this->grafik_model->grafik_satudata($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];
        $difgrow = $growval2 - $growval;
        $sizeyaxis1 = $hasildata['sizeyaxis'];

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1' => $sizeyaxis1]);
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
        $tgl = $this->input->post('tgl');
        $tgl2 = $this->input->post('tgl2');
        $time = $this->input->post('time');
        $time2 = $this->input->post('time2');
        $periode = $this->input->post('periode');
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
        $reqdata['tgl'] = $tgl;
        $reqdata['tgl2'] = $tgl2;
        $reqdata['time'] = $time;
        $reqdata['time2'] = $time2;
        $reqdata['growval'] = $growval;
        $reqdata['growval2'] = $growval2;
        
        $hasildata = $this->grafik_model->grafik_windspeed($reqdata);

        $isigrowday1 = $hasildata['isigrowday1'];
        $isidatagrafik = $hasildata['isidatagrafik'];
        $glabel = $hasildata['glabel'];
        $growval = $hasildata['growval'];
        $linelabel = $hasildata['linelabel'];
        $difgrow = $growval2 - $growval;
        $sizeyaxis1 = $hasildata['sizeyaxis'];

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1' => $sizeyaxis1]);
    }

    public function data_select(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{

            $idlabel = $this->grafik_model->list_data('idselect');

            $textlabel = $this->grafik_model->list_data('textselect');

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

    // public function datatabel(){
    //     $fil1 = $this->input->post('value1');
    //     $fil2 = $this->input->post('value2');
    //     $fil3 = $this->input->post('value3');
    //     $id_user   = $this->session->userdata('id_user');
    //     $fildari   = $this->input->post('value4');
    //     $filsampai = $this->input->post('value5');
    //     $filhour = $this->input->post('value6');
    //     $filperiode = $this->input->post('value7');

    //     if($fil1 == 'DAY_1'){
    //         if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
    //             $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
    //             $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
    //         }
    //     }
    //     if($fil1 == 'HOUR_1'){
    //         if($filhour == '-1'){
    //             $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
    //         }
    //     }

    //     header('Content-Type: application/json');
    //     if ($fil1 == 'DAY_1') {echo $this->grafik_model->json_day($fil1,$fil2,$fil3,$id_user,$fildari,$filsampai,$filperiode);}
    //     if ($fil1 == 'HOUR_1') {echo $this->grafik_model->json_hour($fil1,$fil2,$fil3,$id_user,$filhour,$filperiode);}
    // }

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');
        $tgl = $this->input->post('tgl');
        $tgl2 = $this->input->post('tgl2');
        $time = $this->input->post('time').":00";
        $time2 = $this->input->post('time2').":00";

        $dates1 = date_format(date_create($tgl." ".$time), "Y-m-d H:i:s");
        $dates2 = date_format(date_create($tgl2." ".$time2), "Y-m-d H:i:s");
        $dates['dates1'] = $dates1;
        $dates['dates2'] = $dates2;

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
        if($this->input->post('kateg') == 'temp'){$adata = $this->tabeltemperature($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'hum'){$adata = $this->tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'wind'){$adata = $this->tabelwind($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata[0][0],$inidata[0][1]);}
        if($this->input->post('kateg') == 'feed'){$adata = $this->tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'water'){$adata = $this->tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'press'){$adata = $this->tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'fan'){$adata = $this->tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}

        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

    private function tabeltemperature($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {

        $datsql1  = "SELECT id,growday, date_record";
        $datsql1 .= ",req_temp,".$inidata[1].",".$inidata[2].",".$inidata[3].",".$inidata[4].",".$inidata[5].",".$inidata[0];
        $datsql1 .= " FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "AND date_record >= '".$dates['dates1']."' AND date_record <= '".$dates['dates2']."'";
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $sqlstd = "SELECT std_temp_min,std_temp_max FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

        $dbstd = $this->db->query($sqlstd);

        if($dbstd->num_rows() > 0  and $dbstd->row_array()['std_temp_min'] != ''){
            $dtmin = $dbstd->row_array()['std_temp_min'];
            $dtmax = $dbstd->row_array()['std_temp_max'];
            $minex = explode(',',$dtmin);
            $maxex = explode(',',$dtmax);
        }else{
            $minex = [];
            $maxex = [];
        }

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);

            $noarray = (int)$isidata['growday'] - 1;
            if($noarray <= count($minex)){
                $vstdmin = $minex[((int)$isidata['growday'] - 1)];
                $vstdmax = $maxex[((int)$isidata['growday'] - 1)];
            }else{
                $vstdmin = end($minex);
                $vstdmax = end($maxex);
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
            if(isset($vstdmax)){$fvstdmax = $vstdmax;}else{$fvstdmax = 0;}

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
            $kolomdata[11] = (int)$fvstdmin;
            $kolomdata[12] = (int)$fvstdmax;
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelsatukolom($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {
        $datsql1  = "SELECT id,growday, date_record,";
        $datsql1 .= $inidata[0];
        $datsql1 .= " FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "AND date_record >= '".$dates['dates1']."' AND date_record <= '".$dates['dates2']."'";
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $isistdlabel = [
            'humidity' => ['std_humidity'],
            'feed' => ['std_feed_min','std_feed_max'],
            'water' => ['std_water_min','std_water_max'],
            'static_pressure' => ['std_static_press'],
            'fan' => ['std_fanspeed']
        ];

        $stdlabel = $isistdlabel[$inidata[0]];
        $dtsql = $stdlabel[0];

        if(isset($stdlabel[1])){
            $dtsql .= ",".$stdlabel[1];
        }

        $sqlstd = "SELECT ".$dtsql." FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";
        $dbstd = $this->db->query($sqlstd);

        if($dbstd->num_rows() > 0  and $dbstd->row_array()[$stdlabel[0]] != ''){
            $dtmin = $dbstd->row_array()[$stdlabel[0]];
            $minex = explode(',',$dtmin);

            if(isset($stdlabel[1])){
                $dtmax = $dbstd->row_array()[$stdlabel[1]];
                $maxex = explode(',',$dtmax);
            }
        }else{
            $minex = [];

            if(isset($stdlabel[1])){
                $maxex = [];
            }
        }

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);

            $noarray = (int)$isidata['growday'] - 1;
            if($noarray <= count($minex)){
                $vstdmin = $minex[((int)$isidata['growday'] - 1)];
            }else{
                $vstdmin = end($minex);;
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}

            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata[$inidata[0]];
            $kolomdata[5]  = (int)$fvstdmin;;

            if(isset($stdlabel[1])){
                if($noarray <= count($minex)){
                    $vstdmax = $maxex[((int)$isidata['growday'] - 1)];
                }else{
                    $vstdmax = end($maxex);
                }
                if(isset($vstdmax)){$fvstdmax = $vstdmax;}else{$fvstdmax = 0;}
                $kolomdata[6]  = (int)$fvstdmax;
            }

            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelwind($dates,$id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata1,$inidata2)
    {

        $datsql1  = "SELECT id,growday, date_record,";
        $datsql1 .= $inidata1.",".$inidata2;
        $datsql1 .= " FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "AND date_record >= '".$dates['dates1']."' AND date_record <= '".$dates['dates2']."'";
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $sqlstd = "SELECT std_wind_speed FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

        $dbstd = $this->db->query($sqlstd);

        if($dbstd->num_rows() > 0  and $dbstd->row_array()['std_wind_speed'] != ''){
            $dtmin = $dbstd->row_array()['std_wind_speed'];
            $minex = explode(',',$dtmin);
        }else{
            $minex = [];
        }

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);

            $noarray = (int)$isidata['growday'] - 1;
            if($noarray <= count($minex)){
                $vstdmin = $minex[((int)$isidata['growday'] - 1)];
            }else{
                $vstdmin = end($minex);
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}

            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata[$inidata1];
            $kolomdata[5]  = $isidata[$inidata2];
            $kolomdata[6]  = (int)$fvstdmin;
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

}
