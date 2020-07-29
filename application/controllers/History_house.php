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
        $iniperiode = $this->umum_model->get("(SELECT periode,grow_value FROM image2 WHERE kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC,grow_value DESC LIMIT 1) as data")->row_array();
        if ($inidatafarm['nama_kandang'] == '') {echo 'Silent is gold';return;}

        if($iniperiode['periode'] != ''){
            $setperiode = $iniperiode['periode'];
        }else{
            $setperiode = '0';
        }

        if($iniperiode['periode'] != ''){
            $setgrow = $iniperiode['grow_value'];
        }else{
            $setgrow = '0';
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

    public function grafik(){
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

            if($growval == $growval2){
                $esqlgrow = "AND grow_value = '".$growval."' ";
            }else{
                $esqlgrow = "AND grow_value BETWEEN '".$growval."' AND '".$growval2."' ";
            }

            $esqlperiode = "AND periode = '".$periode."' ";
        }

        $where_kodep = "kode_perusahaan = '".$id_user."'";

        $esql  = "SELECT grow_value AS grow, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value FROM `image2` ";
        $esql .= "WHERE ".$where_kodep." ";
        $esql .= "AND kode_kandang = '".$id_farm."' ";
        $esql .= "AND nama_data = '".$inidata."' ";
        $esql .= "AND kategori = 'HOUR_1' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $label = $this->umum_model->get("(SELECT nama_data FROM kode_data WHERE kode_data = '".$inidata."' LIMIT 1) as data")->row_array()['nama_data'];
        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = $label.$addlabel;
        $linelabel[0] = $label;

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        $adata = [];
        foreach ($dataprimary1 as $value) {
            $adata[] = '('.$value->grow.') - '.$value->jjam_value.':00';
        }
        $isigrowday1 = $adata;

        $bdata = [];
        foreach ($dataprimary1 as $value2) {
            $bdata[] = $value2->isi_value;
        }
        $isidatagrafik[0] = $bdata;
        //END Data Utama

        //Data 2
        $esql2  = "SELECT grow_value AS grow, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value FROM `image2` ";
        $esql2 .= "WHERE ".$where_kodep." ";
        $esql2 .= "AND kode_kandang = '".$id_farm."' ";
        $esql2 .= "AND nama_data = '4096' ";
        $esql2 .= "AND kategori = 'HOUR_1' ";
        $esql2 .= $esqlperiode;
        $esql2 .= $esqlgrow;
        $esql2 .= "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $dataprimary2 = $this->db->query($esql2)->result();
        $cdata2 = [];
        if ($dataprimary2 == null) {
            for ($j=0; $j < count($isigrowday1); $j++) { 
                $cdata2[$j] = 0;
            }
        }else{
            for ($k=0; $k < count($isigrowday1); $k++) { 
                $cdata2[$k] = '';
                foreach ($dataprimary2 as $value3) {
                    if($isigrowday1[$k] == ('('.$value3->grow.') - '.$value3->jjam_value.':00')){
                        $cdata2[$k] = $value3->isi_value;
                    }
                }
                if($cdata2[$k] == '' OR $cdata2[$k] == null){
                    $cdata2[$k] = 0; 
                }
            }
        }

        $isidatagrafik[1] = $cdata2;
        $label2 = $this->umum_model->get("(SELECT nama_data FROM kode_data WHERE kode_data = '4096' LIMIT 1) as data")->row_array()['nama_data'];
        $linelabel[1] = $label2;
        //END Data 2

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel]);
    }

    public function data_select(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2');
            $fil3 = $this->input->post('value3');

            $this->db->select('kode_data AS id,nama_data AS text');
            $this->db->from('kode_data');
            $this->db->where(['aktif'=>'y']);
            $this->db->where("kategori_waktu IN ('2','3')");
            $this->db->order_by('urutan','ASC');
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
                $esqlgrow = "AND grow_value = '".$growval."' ";
            }else{
                $esqlgrow = "AND grow_value BETWEEN '".$growval."' AND '".$growval2."' ";
            }
        }

        $adata = [];
        if($this->input->post('kateg') == 'temp'){$adata = $this->tabeltemperature($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'hum'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'wind'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'feed'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'water'){$adata = $this->tabelwater($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'press'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'fan'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}

        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

    private function tabeltemperature($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {
        $esql1  = "SELECT image2.id,image2.grow_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jjam_value, image2.isi_value FROM `image2` ";
        $esql2 = "WHERE kode_perusahaan = '".$id_user."'";
        $esql3 = "AND kode_kandang = '".$id_farm."' ";
        $esql4 = "AND nama_data = '4096' ";
        $esql5 = "AND kategori = 'HOUR_1' ";
        $esql6 = $esqlperiode;
        $esql7 = $esqlgrow;
        $esql8 = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $datsql1  = $esql1;$datsql1 .= $esql2;$datsql1 .= $esql3;
        $datsql1 .= $esql4;$datsql1 .= $esql5;$datsql1 .= $esql6;
        $datsql1 .= $esql7;$datsql1 .= $esql8;

        $datsql2  = $esql1;$datsql2 .= $esql2;$datsql2 .= $esql3;
        $datsql2 .= "AND nama_data = '".$inidata[1]."' ";
        $datsql2 .= $esql5;$datsql2 .= $esql6;$datsql2 .= $esql7;$datsql2 .= $esql8;

        $datsql3  = $esql1;$datsql3 .= $esql2;$datsql3 .= $esql3;
        $datsql3 .= "AND nama_data = '".$inidata[2]."' ";
        $datsql3 .= $esql5;$datsql3 .= $esql6;$datsql3 .= $esql7;$datsql3 .= $esql8;

        $datsql4  = $esql1;$datsql4 .= $esql2;$datsql4 .= $esql3;
        $datsql4 .= "AND nama_data = '".$inidata[3]."' ";
        $datsql4 .= $esql5;$datsql4 .= $esql6;$datsql4 .= $esql7;$datsql4 .= $esql8;

        $datsql5  = $esql1;$datsql5 .= $esql2;$datsql5 .= $esql3;
        $datsql5 .= "AND nama_data = '".$inidata[4]."' ";
        $datsql5 .= $esql5;$datsql5 .= $esql6;$datsql5 .= $esql7;$datsql5 .= $esql8;

        $datsql6  = $esql1;$datsql6 .= $esql2;$datsql6 .= $esql3;
        $datsql6 .= "AND nama_data = '".$inidata[0]."' ";
        $datsql6 .= $esql5;$datsql6 .= $esql6;$datsql6 .= $esql7;$datsql6 .= $esql8;

        $datsql7  = $esql1;$datsql7 .= $esql2;$datsql7 .= $esql3;
        $datsql7 .= "AND nama_data = '".$inidata[5]."' ";
        $datsql7 .= $esql5;$datsql7 .= $esql6;$datsql7 .= $esql7;$datsql7 .= $esql8;

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);
        $dataprimary2 = $this->db->query($datsql2);
        $dataprimary3 = $this->db->query($datsql3);
        $dataprimary4 = $this->db->query($datsql4);
        $dataprimary5 = $this->db->query($datsql5);
        $dataprimary6 = $this->db->query($datsql6);
        $dataprimary7 = $this->db->query($datsql7);

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $isidata2 = $dataprimary2->row_array($iz);
            $isidata3 = $dataprimary3->row_array($iz);
            $isidata4 = $dataprimary4->row_array($iz);
            $isidata5 = $dataprimary5->row_array($iz);
            $isidata6 = $dataprimary6->row_array($iz);
            $isidata7 = $dataprimary7->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['grow_value'];
            $kolomdata[2]  = $isidata['ttanggal_value'];
            $kolomdata[3]  = $isidata['jjam_value'];
            $kolomdata[4]  = $isidata['isi_value'];
            $kolomdata[5]  = $isidata2['isi_value'];
            $kolomdata[6]  = $isidata3['isi_value'];
            $kolomdata[7]  = $isidata4['isi_value'];
            $kolomdata[8]  = $isidata5['isi_value'];
            $kolomdata[9]  = $isidata6['isi_value'];
            $kolomdata[10] = $isidata7['isi_value'];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelwater($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {
        $esql1  = "SELECT image2.id,image2.grow_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jjam_value, image2.isi_value FROM `image2` ";
        $esql2 = "WHERE kode_perusahaan = '".$id_user."'";
        $esql3 = "AND kode_kandang = '".$id_farm."' ";
        $esql4 = "AND nama_data = '".$inidata[0]."' ";
        $esql5 = "AND kategori = 'HOUR_1' ";
        $esql6 = $esqlperiode;
        $esql7 = $esqlgrow;
        $esql8 = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $datsql1  = $esql1;$datsql1 .= $esql2;$datsql1 .= $esql3;
        $datsql1 .= $esql4;$datsql1 .= $esql5;$datsql1 .= $esql6;
        $datsql1 .= $esql7;$datsql1 .= $esql8;

        $datsql2  = $esql1;$datsql2 .= $esql2;$datsql2 .= $esql3;
        $datsql2 .= "AND nama_data = '".$inidata[1]."' ";
        $datsql2 .= $esql5;$datsql2 .= $esql6;$datsql2 .= $esql7;$datsql2 .= $esql8;

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);
        $dataprimary2 = $this->db->query($datsql2);

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $isidata2 = $dataprimary2->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['grow_value'];
            $kolomdata[2]  = $isidata['ttanggal_value'];
            $kolomdata[3]  = $isidata['jjam_value'];
            $kolomdata[4]  = $isidata['isi_value'];
            $kolomdata[5]  = $isidata2['isi_value'];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    private function tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata)
    {
        $esql1  = "SELECT image2.id,image2.grow_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jjam_value, image2.isi_value FROM `image2` ";
        $esql2 = "WHERE kode_perusahaan = '".$id_user."'";
        $esql3 = "AND kode_kandang = '".$id_farm."' ";
        $esql4 = "AND nama_data = '".$inidata[0]."' ";
        $esql5 = "AND kategori = 'HOUR_1' ";
        $esql6 = $esqlperiode;
        $esql7 = $esqlgrow;
        $esql8 = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $datsql1  = $esql1;$datsql1 .= $esql2;$datsql1 .= $esql3;
        $datsql1 .= $esql4;$datsql1 .= $esql5;$datsql1 .= $esql6;
        $datsql1 .= $esql7;$datsql1 .= $esql8;

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);
        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['grow_value'];
            $kolomdata[2]  = $isidata['ttanggal_value'];
            $kolomdata[3]  = $isidata['jjam_value'];
            $kolomdata[4]  = $isidata['isi_value'];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

}
