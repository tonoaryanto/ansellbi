<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Egg_weight extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->model('grafik_model');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Egg Weight',
            'head1'     => 'Egg Weight',
            'link1'     => 'egg_weight',
            'isi'       => 'egg_weight/list',
            'cssadd'    => 'egg_weight/cssadd',
            'jsadd'     => 'egg_weight/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function input_data(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Input Egg Weight',
            'head1'     => 'Egg Weight',
            'link1'     => 'egg_weight',
            'head2'     => 'Input Egg Weight',
            'link2'     => '#',
            'isi'       => 'egg_weight/form/list',
            'cssadd'    => 'egg_weight/form/cssadd',
            'jsadd'     => 'egg_weight/form/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function save_data(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $tanggal = $this->input->post('tanggal',TRUE);
            $kode_kandang = $this->input->post('kandang');
            $input1 = $this->input->post('input1');
            $growday = $this->input->post('growday');
            $periode = $this->input->post('periode');

            $data = array(
    			'id_farm' => $id_farm,
    			'kode_kandang' => $kode_kandang,
                'tanggal' => $tanggal
			);

            $where = $data;
            $data['data_egg_weight'] = $input1;
            $data['periode'] = $periode;
            $data['growday'] = $growday;
    
            $cekdb = $this->umum_model->get('eggweight',$where)->num_rows();
            if($cekdb > 0){
                $this->umum_model->update('eggweight',$data,$where);
                $message = "Data has been replaced";
            }else{
                $this->umum_model->insert('eggweight',$data);
                $message = 'Data has been saved';
            }
            echo json_encode(['status' => true,'message' => $message]);
        }
    }

    public function grafik(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->input->post('kandang');
        $inidata = 'data_egg_weight';

        $periode = $this->input->post('periode');
        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');


        $cekdb = $this->db->query("SELECT * FROM eggweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ORDER BY periode DESC, growday DESC LIMIT 1")->row_array();

        if($growval == '' and isset($cekdb['growday'])){
            $growval = (int)$cekdb['growday'] - 30;
        }
        if($growval2 == '' and isset($cekdb['growday'])){
            $growval2 = (int)$cekdb['growday'] + 1;
        }

        if($periode == '' and isset($cekdb['periode'])){
            $periode = (int)$cekdb['periode'];
        }

        $esqlperiode = "AND periode = '".$periode."' ";

        if($growval == $growval2){
            $esqlgrow = "AND growday= '".$growval."' ";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
        }

        $esql  = "SELECT id,tanggal,growday,periode,";
        $esql .= $inidata." AS isidata";
        $esql .= " FROM eggweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlgrow;
        $esql .= $esqlperiode;
        $esql .= "ORDER BY growday ASC";

        $label = [
            'data_egg_weight' => 'Egg Weight'
        ];

        $stdlabel = [
            'data_egg_weight' => ['std_egg_weight_min','std_egg_weight_max']
        ];

        if($growval == $growval2){
            $addlabel = ' : Growday '.$growval.' ';
        }else{
            $addlabel = ' : Growday '.$growval.' - '.$growval2;
        }
        $glabel = $label[$inidata].$addlabel;
        $linelabel[0] = $label[$inidata];
        if(isset($stdlabel[$inidata][1])){
            $linelabel[1] = 'Min Standard Value';
            $linelabel[2] = 'Max Standard Value';
        }else{
            $linelabel[1] = 'Standard Value';
        }

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        $dtsql = $stdlabel[$inidata][0];

        if(isset($stdlabel[$inidata][1])){
            $dtsql .= ",".$stdlabel[$inidata][1];
        }

        $sqlstd = "SELECT ".$dtsql." FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

        $dbstd = $this->db->query($sqlstd);

        if($dbstd->num_rows() > 0  and $dbstd->row_array()[$stdlabel[$inidata][0]] != ''){
            $dtmin = $dbstd->row_array()[$stdlabel[$inidata][0]];
            $minex = explode(',',$dtmin);

            if(isset($stdlabel[$inidata][1])){
                $dtmax = $dbstd->row_array()[$stdlabel[$inidata][1]];
                $maxex = explode(',',$dtmax);
            }
        }else{
            $minex = [];

            if(isset($stdlabel[$inidata][1])){
                $maxex = [];
            }
        }

        $adata = [];
        $stdmin = [];

        if(isset($stdlabel[$inidata][1])){
            $stdmax = [];
        }

        foreach ($dataprimary1 as $value) {
            $adata[] = ''.$value->growday.' - '.$value->periode;

            $noarray = (int)$value->growday - 1;
            if($noarray <= count($minex)){
                $vstdmin = $minex[((int)$value->growday - 1)];
            }else{
                $vstdmin = 0;
                // $vstdmin = end($minex);
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
            $stdmin[] = (int)$fvstdmin;

            if(isset($stdlabel[$inidata][1])){
                if($noarray <= count($minex)){
                    $vstdmax = $maxex[((int)$value->growday - 1)];
                }else{
                    $vstdmax = 0;
                    // $vstdmax = end($maxex);
                }
                if(isset($vstdmax)){$fvstdmax = $vstdmax;}else{$fvstdmax = 0;}
                $stdmax[] = (int)$fvstdmax;
            }
        }
        $isigrowday1 = $adata;

        if(empty($isigrowday1[0])){$isigrowday1[0] = 0;}

        $bdata = [];
        foreach ($dataprimary1 as $value2) {
            $bdata[] = floatval($value2->isidata);
        }

        if(empty($bdata[0])){$bdata[0] = 0;}
        if(empty($stdmin[0])){$stdmin[0] = 0;}
        $isidatagrafik[0] = $bdata;
        $isidatagrafik[1] = $stdmin;

        if(isset($stdlabel[$inidata][1])){
            if(empty($stdmax[0])){$stdmax[0] = 0;}
            $isidatagrafik[2] = $stdmax;
        }

        //END Data Utama
        $datamin1[0] = min($bdata);
        $datamax1[0] = max($bdata);

        $datamin1[1] = min($stdmin);

        if(isset($stdlabel[$inidata][1])){
            $datamax1[1] = max($stdmax);
        }else{
            $datamax1[1] = max($stdmin);
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
        if($sizeyaxis1[0] == false){$sizeyaxis1[0] = 0;}
        for ($i=0; $i < $countrange; $i++) { 
            $realmin = $realmin + $dif1range;
            $sizeyaxis1[$i+1] = $realmin;
        }

        if($growval == ''){
            $difgrow = 1;
        }else{
            $difgrow = $growval2 - $growval;
        }

        echo json_encode(['status'=>true,'periode'=>$periode,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'hoursampai'=>$growval2,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1' => $sizeyaxis1]);
    }

    public function getgrow(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $tanggal = date_format(date_create($this->input->post('tanggal')),"Y-m-d");


        $esql = "SELECT periode,growday,date_record FROM `data_record` WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY `data_record`.`periode` DESC,`data_record`.`growday` DESC LIMIT 1";
        $cekdb = $this->db->query($esql);

        if($cekdb->num_rows() > 0){
            $isidb = $cekdb->row_array();
            $diff=date_diff(date_create(date_format(date_create($isidb['date_record']),"Y-m-d")),date_create($tanggal));
            $difgrow = (int)$isidb['growday'] + (int)$diff->format("%R%a");
            
            if($difgrow < 1){
                $cekperiod = (int)$isidb['periode'];

                $esql3 = "SELECT periode,growday,date_record FROM `data_record` WHERE keterangan = 'ok' AND periode != '".$cekperiod."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY `data_record`.`periode` DESC,`data_record`.`growday` DESC LIMIT 1";
                $cekdb3 = $this->db->query($esql3);

                if($cekdb3->num_rows() > 0){
                    $isidb3 = $cekdb3->row_array();
                    $hasilgrow = (int)$isidb3['growday'] + (int)$diff->format("%R%a");
                    $hasilperiode = (int)$isidb3['periode'];
                    if($hasilgrow < 1){
                        $cq = 0;
                    }else{
                        $cq = 1;
                    }
                }else{
                    $cq = 0;
                }
            }else{
                $hasilgrow = $difgrow;
                $hasilperiode = (int)$isidb['periode'];
                $cq = 1;
            }
        }else{
            $cq = 0;
        }

        if($cq == 1){
            echo json_encode(['status'=>true,'growday'=>$hasilgrow,'periode'=>$hasilperiode]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->input->post('kandang');
        $inidata = ['data_egg_weight'];
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

        $datsql1  = "SELECT id,tanggal,growday,periode,";
        $datsql1 .= $inidata[0];
        $datsql1 .= " FROM eggweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY growday ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $isistdlabel = [
            'data_egg_weight' => ['std_egg_weight_min','std_egg_weight_max']
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
                $vstdmin = 0;
                // $vstdmin = end($minex);;
            }
            if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}

            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = date_format(date_create($isidata['tanggal']),"d-m-Y");
            $kolomdata[2]  = $isidata['growday'];
            $kolomdata[3]  = $isidata[$inidata[0]];
            $kolomdata[4]  = (int)$fvstdmin;;

            if(isset($stdlabel[1])){
                if($noarray <= count($minex)){
                    $vstdmax = $maxex[((int)$isidata['growday'] - 1)];
                }else{
                    $vstdmax = 0;
                    // $vstdmax = end($maxex);
                }
                if(isset($vstdmax)){$fvstdmax = $vstdmax;}else{$fvstdmax = 0;}
                $kolomdata[5]  = (int)$fvstdmax;
            }

            $adata[$iz] = $kolomdata;
        }
        //END Data Utama
        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }

    // public function grafikdate(){
    //     //Cek Seesion
    //     $cek_sess = $this->konfigurasi->cek_js();
    //     if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
    //     //END Cek Session

    //     $id_user   = $this->session->userdata('id_user');
    //     $id_farm   = $this->input->post('kandang');
    //     $inidata = 'data_egg_weight';

    //     $growval = $this->input->post('growval');
    //     $growval2 = $this->input->post('growval2');

    //     if($growval == ''){
    //         $date=date_create(date("Y/m/d"));
    //         date_sub($date,date_interval_create_from_date_string("30 days"));
    //         $growval = date_format($date,"Y-m-d");
    //     }
    //     if($growval2 == ''){
    //         $growval2 = date_format(date_create(date("Y/m/d")),"Y-m-d");
    //     }

    //     $esqlgrow = "AND tanggal BETWEEN '".$growval."' AND '".$growval2."' ";

    //     $esql  = "SELECT id,tanggal,";
    //     $esql .= $inidata." AS isidata";
    //     $esql .= " FROM eggweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
    //     $esql .= $esqlgrow;
    //     $esql .= "ORDER BY tanggal ASC";

    //     $label = [
    //         'data_egg_weight' => 'Egg Weight'
    //     ];

    //     $stdlabel = [
    //         'data_egg_weight' => ['std_egg_weight_min','std_egg_weight_max']
    //     ];

    //     if($growval == $growval2){
    //         $addlabel = ' : Date '.tgl_eng($growval).' ';
    //     }else{
    //         $addlabel = ' : Date '.tgl_eng($growval).' - '.tgl_eng($growval2);
    //     }
    //     $glabel = $label[$inidata].$addlabel;
    //     $linelabel[0] = $label[$inidata];
    //     if(isset($stdlabel[$inidata][1])){
    //         $linelabel[1] = 'Min Standard Value';
    //         $linelabel[2] = 'Max Standard Value';
    //     }else{
    //         $linelabel[1] = 'Standard Value';
    //     }

    //     //Data Utama
    //     $dataprimary1 = $this->db->query($esql)->result();

    //     $dtsql = $stdlabel[$inidata][0];

    //     if(isset($stdlabel[$inidata][1])){
    //         $dtsql .= ",".$stdlabel[$inidata][1];
    //     }

    //     $sqlstd = "SELECT ".$dtsql." FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

    //     $dbstd = $this->db->query($sqlstd);

    //     if($dbstd->num_rows() > 0  and $dbstd->row_array()[$stdlabel[$inidata][0]] != ''){
    //         $dtmin = $dbstd->row_array()[$stdlabel[$inidata][0]];
    //         $minex = explode(',',$dtmin);

    //         if(isset($stdlabel[$inidata][1])){
    //             $dtmax = $dbstd->row_array()[$stdlabel[$inidata][1]];
    //             $maxex = explode(',',$dtmax);
    //         }
    //     }else{
    //         $minex = [];

    //         if(isset($stdlabel[$inidata][1])){
    //             $maxex = [];
    //         }
    //     }

    //     $adata = [];
    //     foreach ($dataprimary1 as $value) {
    //         $vgrowday = date_format(date_create($value->tanggal),"d");
    //         $jam = date_format(date_create($value->tanggal),"d-m-Y");
    //         $adata[] = $jam;

    //         $noarray = (int)$vgrowday - 1;
    //         if($noarray <= count($minex)){
    //             $vstdmin = $minex[((int)$vgrowday - 1)];
    //         }else{
    //             $vstdmin = end($minex);;
    //         }
    //         if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
    //         $stdmin[] = (int)$fvstdmin;

    //         if(isset($stdlabel[$inidata][1])){
    //             if($noarray <= count($minex)){
    //                 $vstdmax = $maxex[((int)$vgrowday - 1)];
    //             }else{
    //                 $vstdmax = end($maxex);
    //             }
    //             if(isset($vstdmax)){$fvstdmax = $vstdmax;}else{$fvstdmax = 0;}
    //             $stdmax[] = (int)$fvstdmax;
    //         }
    //     }
    //     $isigrowday1 = $adata;

    //     $bdata = [];
    //     foreach ($dataprimary1 as $value2) {
    //         $bdata[] = floatval($value2->isidata);
    //     }

    //     $isidatagrafik[0] = $bdata;
    //     $isidatagrafik[1] = $stdmin;

    //     if(isset($stdlabel[$inidata][1])){
    //         $isidatagrafik[2] = $stdmax;
    //     }

    //     //END Data Utama
    //     $datamin1[0] = min($bdata);
    //     $datamax1[0] = max($bdata);

    //     $datamin1[1] = min($stdmin);

    //     if(isset($stdlabel[$inidata][1])){
    //         $datamax1[1] = max($stdmax);
    //     }else{
    //         $datamax1[1] = max($stdmin);
    //     }

    //     $realmax = max($datamax1);
    //     $realmin = min($datamin1);

    //     if($realmax < 99){$realmax = $realmax + 2;}
    //     if($realmin > 1){$realmin = $realmin - 1;}

    //     $countrange = 10;
    //     $dif1 = $realmax - $realmin;
    //     if($dif1 == $realmax){$dif1range = $dif1 / 10;}
    //     else{$dif1range = $dif1 / $countrange;}
    //     if($dif1range < 1){$dif1range = 1;}
    //     if(isset(explode(".",$dif1range)[1])){
    //         if(explode(".",$dif1range)[1] >= 1){$dif1range = explode(".",$dif1range)[0] + 1;}
    //     };
    //     $sizeyaxis1[0] = $realmin;
    //     for ($i=0; $i < $countrange; $i++) { 
    //         $realmin = $realmin + $dif1range;
    //         $sizeyaxis1[$i+1] = $realmin;
    //     }

    //     $diff=date_diff(date_create($growval),date_create($growval2));
    //     $difgrow = (int)((int)$diff->format("%a") / 30);
    //     if($difgrow == 0){$difgrow = 1;}

    //     echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'hoursampai'=>$growval2,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1' => $sizeyaxis1]);
    // }

}