<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Body_weight extends CI_Controller {

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
            'txthead1'     => 'Body Weight',
            'head1'     => 'Body Weight',
            'link1'     => 'body_weight',
            'isi'       => 'body_weight/list',
            'cssadd'    => 'body_weight/cssadd',
            'jsadd'     => 'body_weight/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function input_data(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Input Body Weight',
            'head1'     => 'Body Weight',
            'link1'     => 'body_weight',
            'head2'     => 'Input Body Weight',
            'link2'     => '#',
            'isi'       => 'body_weight/form/list',
            'cssadd'    => 'body_weight/form/cssadd',
            'jsadd'     => 'body_weight/form/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function getflock(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $esql2 = "SELECT flock FROM data_kandang WHERE id = '".$kode_kandang."'";
        $cekdb2 = $this->db->query($esql2);
        $isidb21 = $cekdb2->row_array();

        if($cekdb2->num_rows() > 0){
            echo json_encode(['status'=>true,'periode'=>$isidb21['flock']]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }

    public function dtdate(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');

        $where = "id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."'";
        $rhouse = $this->db->query("SELECT periode,growday,tanggal FROM bodyweight WHERE ".$where." ORDER BY tanggal DESC LIMIT 30");
        $house = $rhouse->result();
        $valcur = current($house);
        $valend = end($house);

        if($rhouse->num_rows() > 0){
            $setperiode = $valcur->{'periode'};
            $setgrow1 = $valend->{'growday'};
            $setgrow2 = $valcur->{'growday'};
            $settgl1 = date_format(date_create($valend->{'tanggal'}),"Y-m-d");
            $settgl2 = date_format(date_create($valcur->{'tanggal'}),"Y-m-d");
        }else{
            $setperiode = '1';
            $setgrow1 = '1';
            $setgrow2 = '1';
            $settgl1 = '';
            $settgl2 = '';
        }

        $dtset['periode'] = $setperiode;
        $dtset['tanggal_dari'] = $setgrow1;
        $dtset['tanggal_sampai'] = $setgrow2;
        $dtset['tgl1'] = $settgl1;
        $dtset['tgl2'] = $settgl2;

        if($rhouse->num_rows() > 0){
            echo json_encode(['status' => true, 'dataset' => $dtset]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function changetgl(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $periode = $this->input->post('periode');
        $startgl = $this->input->post('tgl');

        $diff2 = date_format(date_create($startgl),"Y-m-d");
        $where = "tanggal <= '".$diff2."' AND periode = '".$periode."' AND id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."'";
        $house = $this->db->query("SELECT growday,tanggal FROM bodyweight WHERE ".$where." ORDER BY tanggal DESC LIMIT 1")->row_array();

        $date_in = date_format(date_create($house['tanggal']),"Y-m-d");
        $difftgl1 = date_diff(date_create($date_in),date_create($diff2));

        if($house['growday'] != ''){
            $growset = (int)$house['growday'] + (int)$difftgl1->format("%R%a");
        }else{
            $growset = '';
        }

        echo json_encode(['status' => true, 'dataset' => $growset]);
    }

    public function changegrow(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $periode = $this->input->post('periode');
        $stargrow = $this->input->post('grow');

        $where = "growday <= '".$stargrow."' AND periode = '".$periode."' AND id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."'";
        $house = $this->db->query("SELECT periode,growday,tanggal FROM bodyweight WHERE ".$where." ORDER BY tanggal DESC LIMIT 1")->row_array();

        $diffgrow = (int)$stargrow - (int)$house['growday'];

        $date=date_create($house['tanggal']);
        date_modify($date,$diffgrow." days");
        $growset = date_format($date,"Y-m-d");

        if($house['growday'] != ''){
            echo json_encode(['status' => true, 'dataset' => $growset]);
        }else{
            echo json_encode(['status' => false]);
        }
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
            $data['data_body_weight'] = $input1;
            $data['periode'] = $periode;
            $data['growday'] = $growday;
        
            $cekdb = $this->umum_model->get('bodyweight',$where)->num_rows();
            if($cekdb > 0){
                $this->umum_model->update('bodyweight',$data,$where);
                $message = "Data has been replaced";
            }else{
                $this->umum_model->insert('bodyweight',$data);
                $message = 'Data has been saved';
            }
            echo json_encode(['status' => true,'message' => $message]);
        }
    }

    public function getgrow(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $periode = $this->input->post('periode');
        $tanggal = date_format(date_create($this->input->post('tanggal')),"Y-m-d");

        $esql = "SELECT growday,data_body_weight FROM bodyweight WHERE tanggal = '".$tanggal."' AND periode = '".$periode."' AND id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY tanggal DESC LIMIT 1";
        $cekdb = $this->db->query($esql);

        if($cekdb->num_rows() > 0){
            $isidb = $cekdb->row_array();

            $hasilgrow = (int)$isidb['growday'];
            $hasilperiode = (int)$isidb['data_body_weight'];
            $cq = 1;

        }else{
            $tglset = '';
            $house = $this->db->query("SELECT * FROM data_kandang WHERE id = '".$kode_kandang."' AND kode_perusahaan = '".$id_farm."'")->row_array();

            if($house['star_growday'] != ''){
                $date_in = date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s");
                $date_now =$tanggal." ".date_format(date_create($house['reset_time']),"H:i:s");
                $difftgl1 = date_diff(date_create($date_in),date_create($date_now));
                $data['reset_time'] = $house['reset_time'];
        
                $hasilgrow = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");
                $hasilperiode = '';    
                $cq = 1;
            }else{
                $cq = 0;
            }
        }

        if($cq == 1){
            echo json_encode(['status'=>true,'growday'=>$hasilgrow,'periode'=>$hasilperiode]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }

    public function grafik(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->input->post('kandang');
        $inidata = 'data_body_weight';

        $periode = $this->input->post('periode');
        $tgl1 = $this->input->post('tgl1');
        $tgl2 = $this->input->post('tgl2');
        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');

        $esqlperiode = "AND periode = '".$periode."' ";

        if($growval == $growval2){
            $esqlgrow = "AND growday= '".$growval."' ";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
        }

        $esql  = "SELECT id,tanggal,growday,periode,";
        $esql .= $inidata." AS isidata";
        $esql .= " FROM bodyweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlgrow;
        $esql .= $esqlperiode;
        $esql .= "ORDER BY growday ASC";

        $label = [
            'data_body_weight' => 'Body Weight'
        ];

        $stdlabel = [
            'data_body_weight' => ['std_body_weight_min','std_body_weight_max']
        ];

        if($growval == $growval2){
            $addlabel = ' : Growday '.$growval.' ';
            $addtgl = " | Date ".date_format(date_create($tgl1),"d F Y");
        }else{
            $addlabel = ' : Growday '.$growval.' - '.$growval2;
            $addtgl = " | Date ".date_format(date_create($tgl1),"d F Y").' - '.date_format(date_create($tgl2),"d F Y");
        }

        $glabel = $label[$inidata].$addlabel.$addtgl;
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
            $stttgl = date_format(date_create($value->tanggal),"d").date_format(date_create($value->tanggal),"m").date_format(date_create($value->tanggal),"y");
            $adata[] = $stttgl.' - ('.$value->growday.')';

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

        $countrange = 8;
        $dif1 = $realmax - $realmin;
        // if($dif1 == $realmax){$dif1range = $dif1 / 10;}else{$dif1range = $dif1 / $countrange;}
        $dif1range = $dif1 / $countrange;
        if($dif1range < 1){$dif1range = 1;}
        if(isset(explode(".",$dif1range)[1])){
            if(explode(".",$dif1range)[1] >= 1){
                $dif1range = explode(".",$dif1range)[0] + 1;
            }else{
                $dif1range = explode(".",$dif1range)[0];
            }
        }

        if(isset(explode(".",$realmin)[1])){
            if(explode(".",$realmin)[0] >= 1){
                $realmin = explode(".",$realmin)[0] - 1;
            }else{
                $realmin = explode(".",$realmin)[0];
            }
        }

        if(isset(explode(".",$realmax)[1])){
            if(explode(".",$realmax)[0] >= 1){
                $realmax = explode(".",$realmax)[0] + 1;
            }else{
                $realmax = explode(".",$realmax)[0];
            }
        }
        
        $sizeyaxis1[0] = floatval(number_format($realmin,2));
        if(($realmax - $realmin) <= 5){$dif1range = $dif1range / 2;}
        if(($realmax - $realmin) <= 2){$dif1range = ($dif1range * 2) / 5;}
        for ($i=0; $i < $countrange; $i++) { 
            $realmin = $realmin + $dif1range;
            if($realmax <= 200){
                $sizeyaxis1[$i+1] = floatval(number_format($realmin,2));
            }else{
                $sizeyaxis1[$i+1] = (int)number_format($realmin,0,",","");
            }
            if($sizeyaxis1[$i+1] >= $realmax){break;}else{
                if(($i + 1) == $countrange){
                    $countrange = $countrange + 1;
                }                
            }
        }

        if($growval == ''){
            $difgrow = 1;
        }else{
            if(count($isidatagrafik[0]) > 30 ){
                $difgrow = 2;
            }else{
                $difgrow = 1;
            }
        }

        echo json_encode(['status'=>true,'periode'=>$periode,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari'=>$growval,'hoursampai'=>$growval2,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1' => $sizeyaxis1]);
    }

    public function datatable(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->input->post('kandang');
        $inidata = ['data_body_weight'];
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
        $datsql1 .= " FROM bodyweight WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY growday ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $isistdlabel = [
            'data_body_weight' => ['std_body_weight_min','std_body_weight_max']
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
            $kolomdata[4]  = (int)$fvstdmin;

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
}