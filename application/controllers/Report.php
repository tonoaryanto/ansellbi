<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->model('grafik_model');
    }

    public function index(){
        echo "Silent Is Gold";
    }

    public function history_house_day($site=null)
    {
        redirect('report/history_house_hour');
    }

    public function history_house_hour($site=null)
    {
        if($site == null){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Report Histori House',
            'head1'     => 'Report',
            'link1'     => '#',
            'head2'     => 'Histori House',
            'link2'     => 'report/history_house_hour',
            'head3'     => '<b>Multi Data</b>',
            'link3'     => '#',
            'isi'       => 'report/house_hour/list',
            'cssadd'    => 'report/house_hour/cssadd',
            'jsadd'     => 'report/house_hour/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
        }
        if($site == 'myaxis'){
            $this->konfigurasi->cek_url();
            $data = [
                'txthead1'     => 'Report Histori House - Double Yaxis',
                'head1'     => 'Report',
                'link1'     => '#',
                'head2'     => '<b>Histori House</b>',
                'link2'     => 'report/history_house_hour',
                'head3'     => '<b>Yaxis Ganda</b>',
                'link3'     => '#',
                'isi'       => 'report/house_hour/myaxis/list',
                'cssadd'    => 'report/house_hour/myaxis/cssadd',
                'jsadd'     => 'report/house_hour/myaxis/jsadd',
            ];
            $this->load->view('template/wrapper',$data);
        }

    }

    public function datahd($url)
    {
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2')[$url];
            $fil3 = $this->input->post('value3');
            $id_user   = $this->session->userdata('id_user');
            $fildari   = $this->input->post('value4');
            $filsampai = $this->input->post('value5');
            $filperiode = $this->input->post('value7');
            $nomor = $this->input->post('valnomor');
            $valpem = $this->input->post('valpem');

            $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
            $stringkd = [];
            foreach ($namakd as $key) {
                $stringkd[$key->id] = $key->nama_kandang;
            }

            if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
                $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '4096' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3[0]."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
                $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '4096' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3[0]."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }

            $esqlgen  = "SELECT grow_value,isi_value FROM `image2` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = '".$fil1."' ";
            $esqlnamadata = "AND nama_data = '".$fil2."'";
            $esqlgrow = "AND grow_value BETWEEN '".$fildari."' AND '".$filsampai."' ";
            $esqlorder = "ORDER BY grow_value ASC";

            if($fil1 == 'DAY_1'){$addlabel = ' : Grow Day '.$fildari.' s/d '.$filsampai.' ';}
            $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
            $glabel = $label.$addlabel;
            $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';

            //Data Utama
            $esqlprimary  = $esqlgen;
            $esqlprimary .= $esqlnamadata;
            $esqlprimary .= "AND kode_kandang = '".$fil3."'";
            $esqlprimary .= "AND periode = '".$filperiode."' ";
            $esqlprimary .= $esqlgrow;
            $esqlprimary .= $esqlorder;

            $dataprimary = $this->db->query($esqlprimary)->result();

            foreach ($dataprimary as $value) {
                $adata[]  = $value->grow_value;
            }
            $isigrowday = $adata;

            foreach ($dataprimary as $value2) {
                $bdata[] = $value2->isi_value;
            }
            $isiprimary = $bdata;

            //Data Pembanding
            $urutan = 7;
            $countsecond = 0;
            $isisecondary = [];
            for ($i=0; $i < $nomor; $i++) { 
                $urutan = $urutan + 1;
                $esqlsecondary  = $esqlgen;
                $esqlsecondary .= $esqlnamadata;
                $esqlsecondary .= "AND kode_kandang = '".$valpem['valkandang'.$urutan]."'";
                $esqlsecondary .= "AND periode = '".$valpem['valperiode'.$urutan]."' ";
                $esqlsecondary .= $esqlgrow;
                $esqlsecondary .= $esqlorder;
                $datasecondary = $this->db->query($esqlsecondary)->result();
                $cdata = [];
                if ($datasecondary == null) {
                    for ($j=0; $j < count($isigrowday); $j++) { 
                        $cdata[$j] = 0;
                    }
                }else{
                    for ($k=0; $k < count($isigrowday); $k++) { 
                        $cdata[$k] = '';
                        foreach ($datasecondary as $value3) {
                            if($isigrowday[$k] == $value3->grow_value){
                                $cdata[$k] = $value3->isi_value;
                            }
                        }
                        if($cdata[$k] == '' OR $cdata[$k] == null){
                            $cdata[$k] = 0; 
                        }
                    }
                }
                $isisecondary[] = $cdata;
                $countsecond = $countsecond + 1;
                $linelabel[$i+1] = $stringkd[$valpem['valkandang'.$urutan]].' ('.$valpem['valperiode'.$urutan].')';
            }

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday,'data'=>$isiprimary,'label'=>$label,'glabel'=>$glabel,'daydari'=>$fildari,'daysampai'=>$filsampai,'datasecond'=>$isisecondary,'countsecond'=>$countsecond,'linelabel'=>$linelabel]);

    }

    public function datahh($url)
    {
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2')[$url];
            $fil3 = $this->input->post('value3');
            $id_user   = $this->session->userdata('id_user');
            $filhour1 = $this->input->post('value61');
            $filhour2 = $this->input->post('value62');
            $filperiode = $this->input->post('value7');
            $nomor = $this->input->post('valnomor');
            $valpem = $this->input->post('valpem');

            $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
            $stringkd = [];
            foreach ($namakd as $key) {
                $stringkd[$key->id] = $key->nama_kandang;
            }

            if($filhour1 == $filhour2){
                if($filhour1 == '-1'){
                    $filhour1 = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
                    $filhour2 = $filhour1;
                }
                $esqlgrow = "AND grow_value = '".$filhour1."'";
            }else{
                $esqlgrow = "AND grow_value BETWEEN '".$filhour1."' AND '".$filhour2."'";
            }

            $esqlgen  = "SELECT grow_value AS grow, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value FROM `image2` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = '".$fil1."' ";
            $esqlnamadata = "AND nama_data = '".$fil2."'";
            $esqlorder = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

            if($fil1 == 'HOUR_1'){
                if($filhour1 == $filhour2){
                    $addlabel = ' : Grow Day '.$filhour1.' ';
                }else{
                    $addlabel = ' : Grow Day '.$filhour1.' - '.$filhour2.' ';
                }
            }

            $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
            $glabel = $label.$addlabel;
            $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';

            //Data Utama
            $esqlprimary  = $esqlgen;
            $esqlprimary .= $esqlnamadata;
            $esqlprimary .= "AND kode_kandang = '".$fil3."'";
            $esqlprimary .= "AND periode = '".$filperiode."' ";
            $esqlprimary .= $esqlgrow;
            $esqlprimary .= $esqlorder;

            $dataprimary = $this->db->query($esqlprimary)->result();

            $adata = [];
            foreach ($dataprimary as $value) {
                $adata[]  = '('.$value->grow.') - '.$value->jjam_value.':00';
            }
            $isigrowday = $adata;

            $bdata = [];
            foreach ($dataprimary as $value2) {
                $bdata[] = $value2->isi_value;
            }
            $isiprimary = $bdata;

            //Data Pembanding
            $urutan = 7;
            $countsecond = 0;
            $isisecondary = [];
            for ($i=0; $i < $nomor; $i++) { 
                $urutan = $urutan + 1;
                $esqlsecondary  = $esqlgen;
                $esqlsecondary .= $esqlnamadata;
                $esqlsecondary .= "AND kode_kandang = '".$valpem['valkandang'.$urutan]."'";
                $esqlsecondary .= "AND periode = '".$valpem['valperiode'.$urutan]."' ";
                $esqlsecondary .= $esqlgrow;
                $esqlsecondary .= $esqlorder;
                $datasecondary = $this->db->query($esqlsecondary)->result();
                $cdata = [];
                if ($datasecondary == null) {
                    for ($j=0; $j < count($isigrowday); $j++) { 
                        $cdata[$j] = 0;
                    }
                }else{
                    for ($k=0; $k < count($isigrowday); $k++) { 
                        $cdata[$k] = '';
                        foreach ($datasecondary as $value3) {
                            if($isigrowday[$k] == ('('.$value3->grow.') - '.$value3->jjam_value.':00')){
                                $cdata[$k] = $value3->isi_value;
                            }
                        }
                        if($cdata[$k] == '' OR $cdata[$k] == null){
                            $cdata[$k] = 0; 
                        }
                    }
                }
                $isisecondary[] = $cdata;
                $countsecond = $countsecond + 1;
                $linelabel[$i+1] = $stringkd[$valpem['valkandang'.$urutan]].' ('.$valpem['valperiode'.$urutan].')';
            }

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday,'data'=>$isiprimary,'label'=>$label,'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'datasecond'=>$isisecondary,'countsecond'=>$countsecond,'linelabel'=>$linelabel]);

    }

    public function dataaxisd(){
        $data1kandang = $this->input->post('data1kandang');
        $data1periode = $this->input->post('data1periode');
        $data1data    = $this->input->post('data1data');
        $data1posisi  = $this->input->post('data1posisi');
        $data2kandang = $this->input->post('data2kandang');
        $data2periode = $this->input->post('data2periode');
        $data2data    = $this->input->post('data2data');
        $data2posisi  = $this->input->post('data2posisi');
        $fildari      = $this->input->post('value4');
        $filsampai    = $this->input->post('value5');
        $id_user      = $this->session->userdata('id_user');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

        if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
            $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '".$data1data."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
            $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '".$data1data."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
        }

            $esqlgen  = "SELECT grow_value,isi_value FROM `image2` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = 'DAY_1' ";
            $esqlgrow = "AND grow_value BETWEEN '".$fildari."' AND '".$filsampai."' ";
            $esqlorder = "ORDER BY grow_value ASC";

            $label = $this->umum_model->get('kode_data',['kode_data'=>$data1data])->row_array()['nama_data'];
            $glabel = $this->input->post('namagrafik');
            $linelabel[0] = $stringkd[$data1kandang].' ('.$data1periode.') - '.$label;

            //Data Utama
            $esql1  = $esqlgen;
            $esql1 .= "AND nama_data = '".$data1data."'";
            $esql1 .= "AND kode_kandang = '".$data1kandang."'";
            $esql1 .= "AND periode = '".$data1periode."' ";
            $esql1 .= $esqlgrow;
            $esql1 .= $esqlorder;

            $dataprimary1 = $this->db->query($esql1)->result();

            $adata = [];
            foreach ($dataprimary1 as $value) {
                $adata[] = $value->grow_value;
            }
            $isigrowday1 = $adata;

            $bdata = [];
            foreach ($dataprimary1 as $value2) {
                $bdata[] = $value2->isi_value;
            }
            $isidatagrafik[0] = $bdata;
            //END Data Utama

            //Data 2
            $esql2  = $esqlgen;
            $esql2 .= "AND nama_data = '".$data2data."'";
            $esql2 .= "AND kode_kandang = '".$data2kandang."'";
            $esql2 .= "AND periode = '".$data2periode."' ";
            $esql2 .= $esqlgrow;
            $esql2 .= $esqlorder;

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
                        if($isigrowday1[$k] == $value3->grow_value){
                            $cdata2[$k] = $value3->isi_value;
                        }
                    }
                    if($cdata2[$k] == '' OR $cdata2[$k] == null){
                        $cdata2[$k] = 0; 
                    }
                }
            }

            $isidatagrafik[1] = $cdata2;
            $label2 = $this->umum_model->get('kode_data',['kode_data'=>$data2data])->row_array()['nama_data'];
            $linelabel[1] = $stringkd[$data2kandang].' ('.$data2periode.') - '.$label2;
            //END Data 2

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'daydari'=>$fildari,'daysampai'=>$filsampai,'linelabel'=>$linelabel]);
    }

    public function dataaxish(){
        $data1kandang = $this->input->post('data1kandang');
        $data1periode = $this->input->post('data1periode');
        $data1data    = $this->input->post('data1data');
        $data1posisi  = $this->input->post('data1posisi');
        $data2kandang = $this->input->post('data2kandang');
        $data2periode = $this->input->post('data2periode');
        $data2data    = $this->input->post('data2data');
        $data2posisi  = $this->input->post('data2posisi');
        $filhour1      = $this->input->post('value61');
        $filhour2      = $this->input->post('value62');
        $id_user      = $this->session->userdata('id_user');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

            if($filhour1 == $filhour2){
                if($filhour1 == '-1'){
                    $filhour1 = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = 'HOUR_1' AND nama_data = '".$data1data."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
                    $filhour2 = $filhour1;
                }
                $esqlgrow = "AND grow_value = '".$filhour1."'";
            }else{
                $esqlgrow = "AND grow_value BETWEEN '".$filhour1."' AND '".$filhour2."' ";
            }

            $esqlgen  = "SELECT grow_value AS grow, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value FROM `image2` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = 'HOUR_1' ";
            $esqlorder = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

            $label = $this->umum_model->get('kode_data',['kode_data'=>$data1data])->row_array()['nama_data'];
            $glabel = $this->input->post('namagrafik');
            $linelabel[0] = $stringkd[$data1kandang].' ('.$data1periode.') - '.$label;

            //Data Utama
            $esql1  = $esqlgen;
            $esql1 .= "AND nama_data = '".$data1data."'";
            $esql1 .= "AND kode_kandang = '".$data1kandang."'";
            $esql1 .= "AND periode = '".$data1periode."' ";
            $esql1 .= $esqlgrow;
            $esql1 .= $esqlorder;

            $dataprimary1 = $this->db->query($esql1)->result();

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
            $esql2  = $esqlgen;
            $esql2 .= "AND nama_data = '".$data2data."'";
            $esql2 .= "AND kode_kandang = '".$data2kandang."'";
            $esql2 .= "AND periode = '".$data2periode."' ";
            $esql2 .= $esqlgrow;
            $esql2 .= $esqlorder;

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
                        if($isigrowday1[$k] == '('.$value3->grow.') - '.$value3->jjam_value.':00'){
                            $cdata2[$k] = $value3->isi_value;
                        }
                    }
                    if($cdata2[$k] == '' OR $cdata2[$k] == null){
                        $cdata2[$k] = 0; 
                    }
                }
            }

            $isidatagrafik[1] = $cdata2;
            $label2 = $this->umum_model->get('kode_data',['kode_data'=>$data2data])->row_array()['nama_data'];
            $linelabel[1] = $stringkd[$data2kandang].' ('.$data2periode.') - '.$label2;
            //END Data 2

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'linelabel'=>$linelabel]);
    }

    public function datatabel_dyaxish(){
        $fil1 = 'HOUR_1';
        $fil2 = $this->input->post('data1data');
        $fil3 = $this->input->post('data1kandang');
        $id_user   = $this->session->userdata('id_user');
        $filhour1      = $this->input->post('value61');
        $filhour2      = $this->input->post('value62');
        $filperiode = $this->input->post('data1periode');
        $data2kandang = $this->input->post('data2kandang');
        $data2periode = $this->input->post('data2periode');
        $data2data    = $this->input->post('data2data');


        header('Content-Type: application/json');
        if($filhour1 == $filhour2){
            echo $this->grafik_model->json_hour2($fil1,$fil2,$fil3,$id_user,$filhour1,$filperiode,$data2kandang,$data2periode,$data2data);
        }else{
            echo $this->grafik_model->json_hour2_between($fil1,$fil2,$fil3,$id_user,$filhour1,$filhour2,$filperiode,$data2kandang,$data2periode,$data2data);
        }
    }

    public function datatabel_dyaxisd(){
        $fil1 = 'DAY_1';
        $fil2 = $this->input->post('data1data');
        $fil3 = $this->input->post('data1kandang');
        $id_user   = $this->session->userdata('id_user');
        $fildari      = $this->input->post('value4');
        $filsampai    = $this->input->post('value5');
        $filperiode = $this->input->post('data1periode');
        $data2kandang = $this->input->post('data2kandang');
        $data2periode = $this->input->post('data2periode');
        $data2data    = $this->input->post('data2data');

        if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
            $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
            $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
        }

        header('Content-Type: application/json');
        echo $this->grafik_model->json_day2($fil1,$fil2,$fil3,$id_user,$fildari,$filsampai,$filperiode,$data2kandang,$data2periode,$data2data);
    }
}
