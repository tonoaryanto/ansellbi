<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Egg_counter extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('grafik_model');
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'  => 'Egg Counter',
            'head1'     => 'Egg Counter',
            'link1'     => 'egg_counter',
            'isi'       => 'egg_counter/list',
            'cssadd'    => 'egg_counter/cssadd',
            'jsadd'     => 'egg_counter/jsadd',
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
                    $xmenit = (int)str_split(date_format(date_create($isi['date_create']), "i"))[1] - 5;
                    if($xmenit < 0){
                      $xmenit = 0;
                    }else if($xmenit >= 0){
                      $xmenit = 5;
                    }
                    $menit = str_split(date_format(date_create($isi['date_create']), "i"))[0].$xmenit;
                    $jam = date_format(date_create($isi['date_create']), "H").":".$menit.":00";
                    $data2[$nomor] = [
                        'id' => $isi['id'],
                        'periode' => $isi['periode'],
                        'growday' => $isi['growday'],
                        'tanggal' => $tanggal,
                        'jam' => $jam,
                        'eggcounter1' => $isi['eggcounter1'],
                        'eggcounter2' => $isi['eggcounter2'],
                        'eggcounter3' => $isi['eggcounter3'],
                        'eggcounter4' => $isi['eggcounter4'],
                        'eggcounter5' => $isi['eggcounter5'],
                        'eggcounter6' => $isi['eggcounter6'],
                        'eggcounter7' => $isi['eggcounter7'],
                        'eggcounter8' => $isi['eggcounter8']
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
                        'eggcounter1' => '0',
                        'eggcounter2' => '0',
                        'eggcounter3' => '0',
                        'eggcounter4' => '0',
                        'eggcounter5' => '0',
                        'eggcounter6' => '0',
                        'eggcounter7' => '0',
                        'eggcounter8' => '0'
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
        if ($sensor == null) {redirect('egg_counter/farm/'.$idfarm.'/history');}
        if ($sensor != 'history') {redirect('egg_counter/farm/'.$idfarm.'/history');}
        $this->konfigurasi->cek_url();
        $this->session->set_userdata(['idfarm'=>$idfarm]);
        $id_user   = $this->session->userdata('id_user');

        if($sensor == 'history'){$urljs = 'egg_counter-farm-history.js';}

        $inidatafarm = $this->umum_model->get('data_kandang',"id = '".$idfarm."' AND kode_perusahaan = '".$id_user."'")->row_array();
        $iniperiode = $this->umum_model->get("(SELECT periode,growday FROM data_eggcounter WHERE keterangan = 'ok' AND kode_kandang = '".$idfarm."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC,growday DESC LIMIT 1) as data")->row_array();
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
            'txthead1'     => 'Egg Counter - '.$inidatafarm['nama_kandang'],
            'head1'     => 'Egg Counter',
            'link1'     => '#',
            'head2'     => '<b>'.$inidatafarm['nama_kandang'].'</b>',
            'link2'     => 'egg_counter/farm/'.$idfarm,
            'isi'       => 'egg_counter/farm/list',
            'cssadd'    => 'egg_counter/farm/cssadd',
            'jsadd'     => 'egg_counter/farm/jsadd',
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

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = $this->input->post('inidata');

        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');
        $periode = $this->input->post('periode');

        if($growval == $growval2){
            $esqlgrow = "AND growday= '".$growval."' ";
        }else{
            $vgrowval2 = (int)$growval2 + 1;
            $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$vgrowval2."' ";
        }

        $esqlperiode = "AND periode = '".$periode."' ";

        $esql  = "SELECT id,growday, date_record,";
        if($inidata == 'allcounter'){
        $esql .= "eggcounter1 AS isidata,eggcounter2 AS isidata2,eggcounter3 AS isidata3,eggcounter4 AS isidata4,eggcounter5 AS isidata5,eggcounter6 AS isidata6,eggcounter7 AS isidata7,eggcounter8 AS isidata8";
        }else{
        $esql .= $inidata." AS isidata";
        }
        $esql .= " FROM data_eggcounter WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY date_record ASC";

        $label = [
            'allcounter' => 'Egg Counter 1 - 8',
            'sumegg' => 'Total Egg'
        ];

        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = $label[$inidata].$addlabel;

        if($inidata == 'allcounter'){
            $linelabel[0] = 'Egg Counter 1';
            $linelabel[1] = 'Egg Counter 2';
            $linelabel[2] = 'Egg Counter 3';
            $linelabel[3] = 'Egg Counter 4';
            $linelabel[4] = 'Egg Counter 5';
            $linelabel[5] = 'Egg Counter 6';
            $linelabel[6] = 'Egg Counter 7';
            $linelabel[7] = 'Egg Counter 8';
        }else{
            $linelabel[0] = "Standard Value";
            $linelabel[1] = $label[$inidata];
        }

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        if($inidata == 'sumegg'){
            $sqlstd = "SELECT std_egg_counter FROM standar_value WHERE keterangan = 'ok' AND kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

            $dbstd = $this->db->query($sqlstd);

            if($dbstd->num_rows() > 0  and $dbstd->row_array()['std_egg_counter'] != ''){
                $dtmin = $dbstd->row_array()['std_egg_counter'];
                $minex = explode(',',$dtmin);
            }else{
                $minex = [];
            }
        }

        $adata = [];
        if($inidata == 'sumegg'){
            $stdmin = [];
        }

        foreach ($dataprimary1 as $value) {
            $jam = date_format(date_create($value->date_record),"H");
            $adata[] = ''.$value->growday;

            if($inidata == 'sumegg'){
                $noarray = (int)$value->growday - 1;
                if($noarray <= count($minex)){
                    $vstdmin = $minex[((int)$value->growday - 1)];
                }else{
                    $vstdmin = 0;
                    // $vstdmin = end($minex);
                }
                if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
                $stdmin[] = (int)$fvstdmin;
            }
        }
        $isigrowday1 = $adata;

        foreach ($dataprimary1 as $value2) {
            if($inidata == 'allcounter'){
                $cdata1[] = floatval($value2->isidata);
                $cdata2[] = floatval($value2->isidata2);
                $cdata3[] = floatval($value2->isidata3);
                $cdata4[] = floatval($value2->isidata4);
                $cdata5[] = floatval($value2->isidata5);
                $cdata6[] = floatval($value2->isidata6);
                $cdata7[] = floatval($value2->isidata7);
                $cdata8[] = floatval($value2->isidata8);
            }else{
                $cdata1[] = floatval($value2->isidata);
            }
        }

        if($inidata == 'allcounter'){
            if(empty($cdata1[0])){$cdata1[0] = 0;}
            if(empty($cdata2[0])){$cdata2[0] = 0;}
            if(empty($cdata3[0])){$cdata3[0] = 0;}
            if(empty($cdata4[0])){$cdata4[0] = 0;}
            if(empty($cdata5[0])){$cdata5[0] = 0;}
            if(empty($cdata6[0])){$cdata6[0] = 0;}
            if(empty($cdata7[0])){$cdata7[0] = 0;}
            if(empty($cdata8[0])){$cdata8[0] = 0;}

            $isidatagrafik[0] = $cdata1;
            $isidatagrafik[1] = $cdata2;
            $isidatagrafik[2] = $cdata3;
            $isidatagrafik[3] = $cdata4;
            $isidatagrafik[4] = $cdata5;
            $isidatagrafik[5] = $cdata6;
            $isidatagrafik[6] = $cdata7;
            $isidatagrafik[7] = $cdata8;
        }else{
            if(empty($stdmin[0])){$stdmin[0] = 0;}
            if(empty($cdata1[0])){$cdata1[0] = 0;}

            $isidatagrafik[0] = $stdmin;
            $isidatagrafik[1] = $cdata1;
        }

        if($inidata == 'allcounter'){
            $datamax1[0] = max($cdata1);
            $datamax1[1] = max($cdata2);
            $datamax1[2] = max($cdata3);
            $datamax1[3] = max($cdata4);
            $datamax1[4] = max($cdata5);
            $datamax1[5] = max($cdata6);
            $datamax1[6] = max($cdata7);
            $datamax1[7] = max($cdata8);

            $datamin1[0] = min($cdata1);
            $datamin1[1] = min($cdata2);
            $datamin1[2] = min($cdata3);
            $datamin1[3] = min($cdata4);
            $datamin1[4] = min($cdata5);
            $datamin1[5] = min($cdata6);
            $datamin1[6] = min($cdata7);
            $datamin1[7] = min($cdata8);
        }else{
            $datamin1[0] = min($stdmin);
            $datamin1[1] = min($cdata1);

            $datamax1[0] = max($stdmin);    
            $datamax1[1] = max($cdata1);
        }

        $realmax = max($datamax1);
        $realmin = min($datamin1);

        if($realmax < 99){$realmax = $realmax + 2;}
        if($realmin > 1){$realmin = $realmin - 1;}

        $countrange = 10;
        $dif1 = $realmax - $realmin;
        if($dif1 == $realmax){$dif1range = $dif1 / 10;}
        else{$dif1range = $dif1 / $countrange;}
        if($dif1range < 1){$dif1range = 1;}
        if(isset(explode(".",$dif1range)[1])){
            if(explode(".",$dif1range)[1] >= 1){$dif1range = explode(".",$dif1range)[0] + 1;}
        };
        $sizeyaxis1[0] = $realmin;
        for ($i=0; $i < $countrange; $i++) { 
            $realmin = $realmin + $dif1range;
            $sizeyaxis1[$i+1] = $realmin;
        }
        
        $difgrow = $growval2 - $growval;

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

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->session->userdata('idfarm');
        $inidata = ['allcounter'];
        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');
        $periode = $this->input->post('periode');

        if($growval == $growval2){
            $esqlgrow = "AND growday = '".$growval."' ";
        }else{
            $growval2 = (int)$growval2 + 1;
            $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
        }

        $esqlperiode = "AND periode = '".$periode."' ";

        $datsql1  = "SELECT id,date_record,growday,periode,eggcounter1,eggcounter2,eggcounter3,eggcounter4,eggcounter5,eggcounter6,eggcounter7,eggcounter8,sumegg";
        $datsql1 .= " FROM data_eggcounter WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY growday ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $isistdlabel = [
            'allcounter' => ['std_egg_counter']
        ];

        $stdlabel = $isistdlabel[$inidata[0]];
        $dtsql = $stdlabel[0];

        $sqlstd = "SELECT ".$dtsql." FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";
        $dbstd = $this->db->query($sqlstd);

        if($dbstd->num_rows() > 0  and $dbstd->row_array()[$stdlabel[0]] != ''){
            $dtmin = $dbstd->row_array()[$stdlabel[0]];
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
                $vstdmin = 0;
                // $vstdmin = end($minex);;
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}

            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = (int)$fvstdmin;;
            $kolomdata[5]  = $isidata['sumegg'];
            $kolomdata[6]  = $isidata['eggcounter1'];
            $kolomdata[7]  = $isidata['eggcounter2'];
            $kolomdata[8]  = $isidata['eggcounter3'];
            $kolomdata[9]  = $isidata['eggcounter4'];
            $kolomdata[10]  = $isidata['eggcounter5'];
            $kolomdata[11]  = $isidata['eggcounter6'];
            $kolomdata[12]  = $isidata['eggcounter7'];
            $kolomdata[13]  = $isidata['eggcounter8'];

            $adata[$iz] = $kolomdata;
        }
        //END Data Utama
        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

}