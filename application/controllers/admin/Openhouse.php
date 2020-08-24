<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Openhouse extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('grafik_model');
        $this->load->library('datatables');
    }

    public function index(){
        redirect('admin/farm');
    }

    public function datahouse($idfarm,$sensor=null){
        if ($sensor == null) {redirect('admin/openhouse/datahouse/'.$idfarm.'/temperature');}
        if ($sensor != 'temperature' AND $sensor != 'humidity' AND $sensor != 'wind' AND $sensor != 'feed' AND $sensor != 'water' AND $sensor != 'pressure' AND $sensor != 'fan') {redirect('admin/openhouse/datahouse/'.$idfarm.'/temperature');}
        $this->konfigurasi->cek_url();
        $this->session->set_userdata(['idfarm'=>$idfarm]);
        $id_user   = $this->session->userdata('data_openfarm');

        if($sensor == 'temperature'){$urljs = 'admin-open_house-temperaturejs.js';}
        if($sensor == 'humidity'){$urljs = 'admin-open_house-humidityjs.js';}
        if($sensor == 'wind'){$urljs = 'admin-open_house-windjs.js';}
        if($sensor == 'feed'){$urljs = 'admin-open_house-feedjs.js';}
        if($sensor == 'water'){$urljs = 'admin-open_house-waterjs.js';}
        if($sensor == 'pressure'){$urljs = 'admin-open_house-pressurejs.js';}
        if($sensor == 'fan'){$urljs = 'admin-open_house-fanjs.js';}

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

        $isidata = $this->umum_model->get('user',['id' => $id_user])->row_array();

        $data = [
            'txthead1'     => 'Open House - '.$inidatafarm['nama_kandang'],
            'head1'     => 'Data Farm',
            'link1'     => 'admin/farm',
            'head2'     => $isidata['nama_user'],
            'link2'     => 'admin/openfarm/data/'.$id_user,
            'head3'     => '<b>'.$inidatafarm['nama_kandang'].'</b>',
            'link3'     => 'admin/openhouse/datahouse/'.$idfarm,
            'isi'       => 'admin/open_house/list',
            'cssadd'    => 'admin/open_house/cssadd',
            'jsadd'     => 'admin/open_house/jsadd',
            'idfarm'    => $idfarm,
            'iniperiode' => $setperiode,
            'inigrow' => $setgrow,
            'urljs' => $urljs
        ];
        $this->load->view('admin/template/wrapper',$data);
    }

    public function grafik(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('data_openfarm');
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

    public function grafikwp(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('data_openfarm');
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
        $esql .= "AND nama_data = '".$inidata[0][0]."' ";
        $esql .= "AND kategori = 'HOUR_1' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $label = $this->umum_model->get("(SELECT nama_data FROM kode_data WHERE kode_data = '".$inidata[0][0]."' LIMIT 1) as data")->row_array()['nama_data'];
        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = 'Wind Speed'.$addlabel;
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
        $esql2 .= "AND nama_data = '".$inidata[0][1]."' ";
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
        $label2 = $this->umum_model->get("(SELECT nama_data FROM kode_data WHERE kode_data = '".$inidata[0][1]."' LIMIT 1) as data")->row_array()['nama_data'];
        $linelabel[1] = $label2;
        //END Data 2

        //Data 3
        $esql3  = "SELECT grow_value AS grow, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value FROM `image2` ";
        $esql3 .= "WHERE ".$where_kodep." ";
        $esql3 .= "AND kode_kandang = '".$id_farm."' ";
        $esql3 .= "AND nama_data = '".$inidata[0][2]."' ";
        $esql3 .= "AND kategori = 'HOUR_1' ";
        $esql3 .= $esqlperiode;
        $esql3 .= $esqlgrow;
        $esql3 .= "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $dataprimary3 = $this->db->query($esql3)->result();
        $cdata3 = [];
        if ($dataprimary3 == null) {
            for ($j=0; $j < count($isigrowday1); $j++) { 
                $cdata3[$j] = 0;
            }
        }else{
            for ($k=0; $k < count($isigrowday1); $k++) { 
                $cdata3[$k] = '';
                foreach ($dataprimary3 as $value4) {
                    if($isigrowday1[$k] == ('('.$value4->grow.') - '.$value4->jjam_value.':00')){
                        $cdata3[$k] = $value4->isi_value;
                    }
                }
                if($cdata3[$k] == '' OR $cdata3[$k] == null){
                    $cdata3[$k] = 0; 
                }
            }
        }

        $isidatagrafik[2] = $cdata3;
        $label3 = $this->umum_model->get("(SELECT nama_data FROM kode_data WHERE kode_data = '".$inidata[0][2]."' LIMIT 1) as data")->row_array()['nama_data'];
        $linelabel[2] = $label3;
        //END Data 3

        echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'linelabel'=>$linelabel]);
    }

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $radio = $this->input->post('radio');
        $id_user   = $this->session->userdata('data_openfarm');
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
        if($this->input->post('kateg') == 'wind'){$adata = $this->tabelwind($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata[0][0],$inidata[0][1],$inidata[0][2]);}
        if($this->input->post('kateg') == 'feed'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'water'){$adata = $this->tabelwater($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'press'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}
        if($this->input->post('kateg') == 'fan'){$adata = $this->tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata);}

        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

    private function tabeltemperature($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata){
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

    private function tabelwater($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata){
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

    private function tabelsatukolom($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata){
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

    private function tabelwind($id_user,$id_farm,$esqlperiode,$esqlgrow,$inidata1,$inidata2,$inidata3){
        $esql1  = "SELECT image2.id,image2.grow_value, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jjam_value, image2.isi_value FROM `image2` ";
        $esql2 = "WHERE kode_perusahaan = '".$id_user."'";
        $esql3 = "AND kode_kandang = '".$id_farm."' ";
        $esql4 = "AND nama_data = '".$inidata1."' ";
        $esql5 = "AND kategori = 'HOUR_1' ";
        $esql6 = $esqlperiode;
        $esql7 = $esqlgrow;
        $esql8 = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $datsql1  = $esql1;$datsql1 .= $esql2;$datsql1 .= $esql3;
        $datsql1 .= $esql4;$datsql1 .= $esql5;$datsql1 .= $esql6;
        $datsql1 .= $esql7;$datsql1 .= $esql8;

        $esql41 = "AND nama_data = '".$inidata2."' ";
        $datsql2  = $esql1 ;$datsql2 .= $esql2;$datsql2 .= $esql3;
        $datsql2 .= $esql41;$datsql2 .= $esql5;$datsql2 .= $esql6;
        $datsql2 .= $esql7 ;$datsql2 .= $esql8;

        $esql42 = "AND nama_data = '".$inidata3."' ";
        $datsql3  = $esql1 ;$datsql3 .= $esql2;$datsql3 .= $esql3;
        $datsql3 .= $esql42;$datsql3 .= $esql5;$datsql3 .= $esql6;
        $datsql3 .= $esql7 ;$datsql3 .= $esql8;

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);
        $dataprimary2 = $this->db->query($datsql2);
        $dataprimary3 = $this->db->query($datsql3);
        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $isidata2 = $dataprimary2->row_array($iz);
            $isidata3 = $dataprimary3->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['grow_value'];
            $kolomdata[2]  = $isidata['ttanggal_value'];
            $kolomdata[3]  = $isidata['jjam_value'];
            $kolomdata[4]  = $isidata['isi_value'];
            $kolomdata[5]  = $isidata2['isi_value'];
            $kolomdata[6]  = $isidata3['isi_value'];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        return $adata;
    }

    public function dataalarm($idfarm){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('data_openfarm');
        $inidatafarm = $this->umum_model->get('data_kandang',"id = '".$idfarm."' AND kode_perusahaan = '".$id_user."'")->row_array();
        $isidata = $this->umum_model->get('user',['id' => $id_user])->row_array();

        $data_farm = $this->umum_model->get("(SELECT * FROM (SELECT periode, grow_value,tanggal_value as settgl,jam_value as settime,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value FROM image2 WHERE kategori = 'HOUR_1' AND kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC, grow_value DESC LIMIT 1) as data GROUP BY settgl DESC, LPAD(SUBSTRING_INDEX(settime, '-', 1), 2, '0') DESC LIMIT 1) as data2")->row_array();

        $data = [
            'txthead1'     => 'Open House - '.$inidatafarm['nama_kandang'],
            'head1'     => 'Data Farm',
            'link1'     => 'admin/farm',
            'head2'     => $isidata['nama_user'],
            'link2'     => 'admin/openfarm/data/'.$id_user,
            'head3'     => '<b>'.$inidatafarm['nama_kandang'].'</b>',
            'link3'     => 'admin/openhouse/dataalarm/'.$idfarm,
            'isi'       => 'admin/open_house/history_alarm/list',
            'cssadd'    => 'admin/open_house/history_alarm/cssadd',
            'jsadd'     => 'admin/open_house/history_alarm/jsadd',
            'data_farm' => $data_farm
        ];
        $this->load->view('admin/template/wrapper',$data);
    }

    public function datajson(){
        $id_user = $this->session->userdata('data_openfarm');
        $data_select = $this->input->post('value1');
        $dari = date_format(date_create($this->input->post('value2')), 'Y-m-d');
        $sampai = date_format(date_create($this->input->post('value3')), 'Y-m-d');
        $data_periode = $this->input->post('value4');

        $where  = "id_user = '".$id_user."' ";
        $where .= "AND kode_kandang = '".$data_select."' ";

        $where1  = $where;
        if($dari == $sampai){
            $where1 .= "AND tanggal = '".$dari."' ";
        }else{
            $where1 .= "AND tanggal >= '".$dari."' ";
            $where1 .= "AND tanggal <= '".$sampai."' ";    
        }
        $where1 .= "AND periode = '".$data_periode."' ";

        $raw_group = $this->db->query("SELECT tanggal FROM history_alarm WHERE ".$where1." GROUP BY tanggal");

        if ($raw_group->num_rows() >= 1) {
        ?>
        <ul class="timeline">
        <?php
        $no1 = 0; 
        foreach ($raw_group->result() as $value1) {
            $where2  = $where;
            $where2 .= "AND tanggal = '".$value1->tanggal."' ";
            $raw_alarm = $this->umum_model->get('history_alarm',$where2)->result();

            ?>
            <!-- timeline time label -->
            <li class="time-label">
                <span class="<?php echo $this->getbg($this->umum_model->acak())?>">
                  <?php echo tgl_indo_hari($value1->tanggal);?>
                </span>
            </li>
            <!-- /.timeline-label -->
            <?php
            $no2 = 0;
            foreach ($raw_alarm as $value2) {
            ?>
            <!-- timeline item -->
            <li>
            <i class="fa <?php echo $this->type_icon($value2->type); ?>"></i>

            <div class="timeline-item">
              <span class="time"><b><i class="fa fa-clock-o"></i> <?php echo $value2->jam?></b></span>

              <h3 class="timeline-header"><a href="javascript:void(0);" data-toggle="collapse" data-target="#colap<?php echo $no1.$no2?>"><?php echo $this->type($value2->type); ?></a> <?php echo $this->param($value2->alarm_param,$value2->data5); ?></h3>

              <div class="timeline-body">
                <div id="colap<?php echo $no1.$no2?>" class="collapse">
                    <p>
                        <style type="text/css">tr:nth-child(odd){background: #f7f7f759} tr:nth-child(even){background: #bdd0fb36;} td{vertical-align: top;padding-bottom: 5px;}</style>
                        <table border="0" style="width: 100%">
                            <tr>
                                <td style="width: 130px">Grow Day</td>
                                <td style="width: 1px">&nbsp;:&nbsp;</td>
                                <td><?php echo $value2->growday; ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 1</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm1 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm1);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm1($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 1</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable1 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable1);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm1($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 2</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm2 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm2);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm2($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 2</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable2 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable2);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm2($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 3</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm3 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm3);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm3($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 3</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable3 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable3);
                                    for ($i=0; $i < count($data); $i++) {
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm3($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Require Temperature</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->req_temp == '-999.9'){echo 'Tidak digunakan';
                                }else if($value2->req_temp == '777.7'){
                                    echo 'Tidak Terhubung';
                                }else{
                                    echo $value2->req_temp.' <span>&#8451;</span>';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>In Temperature</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->in_temp == '-999.9'){echo 'Tidak digunakan';
                                }else if($value2->req_temp == '777.7'){
                                    echo 'Tidak Terhubung';
                                }else{
                                    echo $value2->in_temp.' <span>&#8451;</span>';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->humidity == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->humidity;
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Weight</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->weight == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->weight.' gr';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->state == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->state;
                                } ?></td>
                            </tr>
                        </table>
                    </p>
                </div>
              </div>
            </div>
            </li>
            <!-- END timeline item -->
            <?php
            $no2++;
            }
        $no1++;
        }
            ?>
              <li>
                <i class="fa fa-clock-o bg-gray"></i>
              </li>
            </ul>
            <?php
        }else{
            ?>
            <p style="text-align: center;color:#888;">- Data tidak tersedia -</p>
            <?php 
        }

    }

    private function alarm1($value){
        $data = [
            '1' => 'Cold',
            '2' => 'Hot',
            '3' => 'Memori',
            '4' => 'All Sensors',
            '5' => 'One Sensor',
            '7' => 'Silo 1 Empty',
            '8' => 'Silo 2 Empty',
            '9' => 'Silo Error',
        ];

        return $data[$value];
    }

    private function alarm2($value){
        $data = [
            '1' => 'Water Overflow',
            '2' => 'Water Stoppage',
            '3' => 'AC Power Alarm',
            '4' => 'Alarm Test',
            '5' => 'Trolleys At Start Input',
            '6' => 'Trolleys Fill Alarm',
            '7' => 'Trolleys Move Alarm',
            '8' => 'Low Humidity Alarm',
            '9' => 'Hight Humidity Alarm',
        ];

        return $data[$value];
    }

    private function alarm3($value){
        $data = [
            '1' => 'Egg Floor',
            '2' => 'Low Wind Speed',
            '3' => 'Height Wind Speed',
            '4' => 'Panel Alarm',
        ];

        return $data[$value];
    }

    private function getbg($value){
        if($value == 5){$value = 0;}
        if($value == 6){$value = 1;}
        if($value == 7){$value = 2;}
        if($value == 8){$value = 3;}
        if($value == 9){$value = 4;}
        $data = [
            '0' => 'bg-red',
            '1' => 'bg-green',
            '2' => 'bg-blue',
            '3' => 'bg-purple',
            '4' => 'bg-orange',
        ];

        return $data[$value];
    }

    private function type($value){
        $data = [
            '1' => 'Alarm On',
            '2' => 'Alarm Off',
            '3' => 'Start Up',
            '4' => 'Shut Down',
            '5' => 'Params',
        ];

        return $data[$value];
    }

    private function type_icon($value){
        $data = [
            '1' => 'fa-dot-circle-o bg-green',
            '2' => 'fa-circle-o bg-red',
            '3' => 'fa-plug bg-blue',
            '4' => 'fa-power-off bg-black',
            '5' => 'fa-th-list bg-purple',
        ];

        return $data[$value];
    }

    private function param($value,$value2){
        $data = [
            '0'  => '',
            '4096'  => '(Require Temp.)',
            '3'     => '(Al Disable)',
            '8'     => '(Al. Dis. T2)',
            '12432' => '(Weight)',
            '800'   => '(Grow day)',
            '1829'  => '(Panel Al. Dis.)',
            '1828'  => '(Panel)',
            '3154'  => '(Type 1)',
            '3170'  => '(Type 2)',
            '7218'  => '(Temp Avg)',
            '3142'  => '(Humidity Mes.)',
            '3147'  => '(System state)',
            '3708'  => '(Type 3)',
            '1186'  => '(Al. Dis. T3)',
        ];

        if($value2 == 0){$value2 = '';}
        $hasil = $value2.$data[$value];
        return $hasil;
    }
}
