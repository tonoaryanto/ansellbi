<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Population extends CI_Controller {

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
            'txthead1'     => 'Population',
            'head1'     => 'Population',
            'link1'     => 'population',
            'isi'       => 'population/list',
            'cssadd'    => 'population/cssadd',
            'jsadd'     => 'population/jsadd',
            'isijs'     => 'js'
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function mortality(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Population',
            'head1'     => 'Population',
            'link1'     => 'population',
            'isi'       => 'population/list',
            'cssadd'    => 'population/cssadd',
            'jsadd'     => 'population/jsadd',
            'isijs'     => 'mortalityjs'
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function selection(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Population',
            'head1'     => 'Population',
            'link1'     => 'population',
            'isi'       => 'population/list',
            'cssadd'    => 'population/cssadd',
            'jsadd'     => 'population/jsadd',
            'isijs'     => 'selectionjs'
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function input_data(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data = [
            'txthead1'     => 'Input Population',
            'head1'     => 'Population',
            'link1'     => 'population',
            'head2'     => 'Input Population',
            'link2'     => '#',
            'isi'       => 'population/form/list',
            'cssadd'    => 'population/form/cssadd',
            'jsadd'     => 'population/form/jsadd',
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
            $growday = $this->input->post('growday');
            $periode = $this->input->post('periode');
            $stp = $this->input->post('stpopulation');
            $mortality = $this->input->post('mortality');
            $selection = $this->input->post('selection');

            if($stp == "true"){
                $jml = $this->input->post('population');
            }else{
                $jml = $this->input->post('birdin');
            }

            $newjml = (int)$jml - (int)$mortality - (int)$selection;

            $data = array(
    			'id_farm' => $id_farm,
    			'kode_kandang' => $kode_kandang,
    			'periode' => $periode,
    			'growday' => $growday,
                'tanggal' => $tanggal
			);

            $where = $data;
            $cekbird = $this->umum_model->get('population',['id_farm' => $id_farm,'kode_kandang' => $kode_kandang])->num_rows();

            if($cekbird < 1){
                $data['keterangan'] = 'birdin';
            }

            $data['birdin'] = $this->input->post('birdin');;
            $data['mortality'] = $mortality;
            $data['selection'] = $selection;
            $data['population'] = $jml;
            $data['afterpopulation'] = $newjml;
        
            $cekdb = $this->umum_model->get('population',$where)->num_rows();
            if($cekdb > 0){
                $this->umum_model->update('population',$data,$where);
                $message = "Data has been replaced";
            }else{
                $this->umum_model->insert('population',$data);
                $message = 'Data has been saved';
            }
            echo json_encode(['status' => true,'message' => $message]);
        }
    }

    public function getgrow(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $tanggal = $this->input->post('tanggal');

        $esql = "SELECT periode,growday,date_record FROM `data_record` WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY `data_record`.`periode` DESC,`data_record`.`growday` DESC LIMIT 1";
        $cekdb = $this->db->query($esql);

        $esql2 = "SELECT birdin,population,keterangan,mortality,selection FROM population WHERE tanggal = '".$tanggal."' AND id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY periode DESC, growday DESC LIMIT 1";
        $cekdb2 = $this->db->query($esql2);

        if($cekdb->num_rows() > 0){
            $isidb = $cekdb->row_array();
            $diff=date_diff(date_create($isidb['date_record']),date_create($tanggal));
            $difgrow = (int)$isidb['growday'] + (int)$diff->format("%R%a");
            
            if($difgrow < 1){
                $cekperiod = (int)$isidb['periode'] - 1;

                $esql3 = "SELECT periode,growday,date_record FROM `data_record` WHERE periode = '".$cekperiod."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY `data_record`.`periode` DESC,`data_record`.`growday` DESC LIMIT 1";
                $cekdb3 = $this->db->query($esql3);
        
                if($cekdb3->num_rows() > 0){
                    $isidb3 = $cekdb3->row_array();
                    $hasilgrow = (int)$isidb3['growday'] + $difgrow;
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

        if($cekdb2->num_rows() > 0){
            $isidb2 = $cekdb2->row_array();
            if($isidb2['keterangan'] == 'birdin'){
                $pp = [false,$isidb2['birdin'],$isidb2['mortality'],$isidb2['selection']];
            }else{
                $pp = [true,$isidb2['population'],$isidb2['mortality'],$isidb2['selection']];
            }
        }else{
            $esql3 = "SELECT birdin,population,afterpopulation,keterangan,mortality,selection,tanggal FROM population WHERE id_farm = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ORDER BY periode DESC, growday DESC LIMIT 1";
            $cekdb3 = $this->db->query($esql3);
            $isidb3 = $cekdb3->row_array();
            $ttg1   = (int)(date_format(date_create($isidb3['tanggal']),"Y").date_format(date_create($isidb3['tanggal']),"m").date_format(date_create($isidb3['tanggal']),"d"));
            $ttg2  = (int)(date_format(date_create($tanggal),"Y").date_format(date_create($tanggal),"m").date_format(date_create($tanggal),"d"));

            if($cekdb3->num_rows() > 0){
                if($isidb3['keterangan'] == 'birdin' and $ttg2 == $ttg1){
                    $pp = [false,$isidb3['birdin'],$isidb3['mortality'],$isidb3['selection']];
                }else{
                    if($ttg2 > $ttg1){
                        $pp = [true,$isidb3['afterpopulation'],'',''];
                    }else{
                        $pp = [true,$isidb3['population'],$isidb3['mortality'],$isidb3['selection']];
                    }
                }
            }else{
                $pp = [false,'','',''];
            }
        }


        if($cq == 1){
            echo json_encode(['status'=>true,'growday'=>$hasilgrow,'periode'=>$hasilperiode,'pp'=>$pp]);
        }else{
            echo json_encode(['status'=>false,'pp'=>$pp]);
        }
    }

    public function grafik(){
        //Cek Seesion
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        //END Cek Session

        $id_user   = $this->session->userdata('id_user');
        $id_farm   = $this->input->post('kandang');
        $inidata = $this->input->post('inidata');

        $periode = $this->input->post('periode');
        $growval = $this->input->post('growval');
        $growval2 = $this->input->post('growval2');


        $cekdb = $this->db->query("SELECT * FROM population WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ORDER BY periode DESC, growday DESC LIMIT 1")->row_array();

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
        if($inidata[0] == 'afterpopulation'){
            $esql .= $inidata[0]." AS isidata,";
            $esql .= $inidata[1]." AS isidata2,";
            $esql .= $inidata[2]." AS isidata3";
        }else{
            $esql .= $inidata[0]." AS isidata";
        }
        $esql .= " FROM population WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlgrow;
        $esql .= $esqlperiode;
        $esql .= "ORDER BY growday ASC";

        $esql2  = "SELECT id,tanggal,growday,periode,birdin";
        $esql2 .= " FROM population WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql2 .= "AND keterangan = 'birdin'";
        $esql2 .= $esqlperiode;
        $esql2 .= "ORDER BY growday ASC";

        $databirdin = $this->db->query($esql2)->row_array();

        $label = [
            'afterpopulation' => 'Population',
            'mortality' => 'Mortality',
            'selection' => 'Selection'
        ];

        if($inidata[0] != 'afterpopulation'){
            $stdlabel = [
                'mortality' => ['std_mortality'],
                'selection' => ['std_selection']
            ];    
        }

        if($growval == $growval2){
            $addlabel = ' : Growday '.$growval.' ';
        }else{
            $addlabel = ' : Growday '.$growval.' - '.$growval2;
        }
        $glabel = $label[$inidata[0]].$addlabel;
        $linelabel[0] = $label[$inidata[0]];

        if($inidata[0] != 'afterpopulation'){
            $linelabel[1] = 'Standard Value';
        }else{
            $linelabel[1] = $label[$inidata[1]];
            $linelabel[2] = $label[$inidata[2]];
        }

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        if($inidata[0] != 'afterpopulation'){
            $dtsql = $stdlabel[$inidata[0]][0];

            $sqlstd = "SELECT ".$dtsql." FROM standar_value WHERE kode_farm = '".$id_user."' AND kode_kandang = '".$id_farm."'";

            $dbstd = $this->db->query($sqlstd);

            if($dbstd->num_rows() > 0  and $dbstd->row_array()[$stdlabel[$inidata[0]][0]] != ''){
                $dtmin = $dbstd->row_array()[$stdlabel[$inidata[0]][0]];
                $minex = explode(',',$dtmin);
            }else{
                $minex = [];
            }
        }

        $adata = [];

        if($inidata[0] != 'afterpopulation'){
            $stdmin = [];
        }

        foreach ($dataprimary1 as $value) {
            $adata[] = ''.$value->growday;

            if($inidata[0] != 'afterpopulation'){
                $noarray = (int)$value->growday - 1;
                if($noarray <= count($minex)){
                    $vstdmin = $minex[((int)$value->growday - 1)];
                }else{
                    $vstdmin = 0;
                    // $vstdmin = end($minex);
                }
                if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
                $stdmin[] = $fvstdmin;
            }
        }
        $isigrowday1 = $adata;

        if(empty($isigrowday1[0])){$isigrowday1[0] = 0;}
        
        $bdata = [];
        if($inidata[0] == 'afterpopulation'){
            $cdata = [];
            $ddata = [];
            $vmor = 0;
            $vsel = 0;
        }else{
            $vmor2 = 0;
        }
        foreach ($dataprimary1 as $value2) {
            if($inidata[0] == 'afterpopulation'){
                $bdata[] = floatval($value2->isidata);
                $vmor = $vmor + floatval($value2->isidata2);
                $vsel = $vsel + floatval($value2->isidata3);
                $cdata[] = $vmor;
                $ddata[] = $vsel;
            }else{
                $mort = 0;
                $vmor2 = $vmor2 + floatval($value2->isidata);
                $mort = ($vmor2 / $databirdin['birdin']) * 100;
                $bdata[] = $mort;
            }
        }

        if(empty($bdata[0])){$bdata[0] = 0;}
        $isidatagrafik[0] = $bdata;
        $datamin1[0] = min($bdata);
        $datamax1[0] = max($bdata);

        if($inidata[0] != 'afterpopulation'){
            if(empty($stdmin[0])){$stdmin[0] = 0;}
            $isidatagrafik[1] = $stdmin;

            $datamin1[1] = min($stdmin);
            $datamax1[1] = max($stdmin);
        }else{
            if(empty($cdata[0])){$cdata[0] = 0;}
            $isidatagrafik[1] = $cdata;
            $datamin1[1] = min($cdata);
            $datamax1[1] = max($cdata);    
            if(empty($ddata[0])){$ddata[0] = 0;}
            $isidatagrafik[2] = $ddata;
            $datamin1[2] = min($ddata);
            $datamax1[2] = max($ddata);
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
        $inidata = $this->input->post('inidata');
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
        if($inidata[0] != 'afterpopulation'){
            $datsql1 .= $inidata[0];
        }else{
            $datsql1 .= $inidata[0].','.$inidata[1].','.$inidata[2];
        }
        $datsql1 .= " FROM population WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql1 .= $esqlperiode;
        $datsql1 .= $esqlgrow;
        $datsql1 .= "ORDER BY growday ASC";

        $datsql2  = "SELECT id,tanggal,growday,periode,birdin";
        $datsql2 .= " FROM population WHERE id_farm = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $datsql2 .= "AND keterangan = 'birdin' ";
        $datsql2 .= $esqlgrow;
        $datsql2 .= "ORDER BY growday ASC";

        
        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $databirdin = $this->db->query($datsql2)->row_array();

        if($inidata[0] != 'afterpopulation'){
            $isistdlabel = [
                'mortality' => ['std_mortality'],
                'selection' => ['std_selection']
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
        }

        $adata = [];

        if($inidata[0] != 'afterpopulation'){
            $vmor2 = 0;
        }else{
            $vmor = 0;
            $vsel = 0;
        }

        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);

            if($inidata[0] != 'afterpopulation'){
                $noarray = (int)$isidata['growday'] - 1;
                if($noarray <= count($minex)){
                    $vstdmin = $minex[((int)$isidata['growday'] - 1)];
                }else{
                    $vstdmin = 0;
                    // $vstdmin = end($minex);;
                }
                if(isset($vstdmin)){$fvstdmin = $vstdmin;}else{$fvstdmin = 0;}
            }

            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = date_format(date_create($isidata['tanggal']),"d-m-Y");
            $kolomdata[2]  = $isidata['growday'];

            if($inidata[0] != 'afterpopulation'){
                $mort = 0;
                $vmor2 = $vmor2 + floatval($isidata[$inidata[0]]);
                $mort  = ($vmor2 / $databirdin['birdin']) * 100;
                $vstdv = 0;
                $vstdv = ($fvstdmin * $databirdin['birdin']) / 100;
    
                $kolomdata[3]  = $databirdin['birdin'];
                $kolomdata[4]  = $isidata[$inidata[0]];
                $kolomdata[5]  = $vmor2.' ('.$mort.')';
                $kolomdata[6]  = $vstdv.' ('.$fvstdmin.')';
            }else{
                $vmor = $vmor + floatval($isidata[$inidata[1]]);
                $vsel = $vsel + floatval($isidata[$inidata[2]]);
                $kolomdata[3]  = $isidata[$inidata[0]];
                $kolomdata[4]  = $isidata[$inidata[1]].' ('.$vmor.')';
                $kolomdata[5]  = $isidata[$inidata[2]].' ('.$vsel.')';
            }

            $adata[$iz] = $kolomdata;
        }
        //END Data Utama
        echo json_encode(['status' => true, 'dataSet' => $adata]);
    }    
}