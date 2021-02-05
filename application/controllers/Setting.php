<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    public function index(){
        echo "Silent Is Gold";
    }

    public function standard_value($form=null,$dataform=null){
        $this->konfigurasi->cek_url();
        if($dataform != null and $this->tpvalue($dataform) == ''){redirect(base_url('setting/standard_value/'));}
        $dtitle = explode('_',$dataform);
        $datatitle = ucfirst($dtitle[0]);
        if(isset($dtitle[1])){$datatitle .= ' '.ucfirst($dtitle[1]);}
        if($form == 'set'){
            $data = [
                'txthead1'  => 'Input Standard '.$datatitle,
                'head1'     => 'Setting',
                'link1'     => '#',
                'head2'     => 'Standard Value',
                'link2'     => 'setting/standard_value',
                'head3'     => 'Form',
                'link3'     => '#',
                'isi'       => 'setting/standard_value/form',
                'cssadd'    => 'setting/standard_value/cssadd',
                'jsadd'     => $this->setjs($dataform),
                'texttitle' => $datatitle,
                'tpval' => $this->tpvalue($dataform)
            ];
            $this->load->view('template/wrapper',$data);
        }else{
            $data = [
                'txthead1'  => 'Standard Value',
                'head1'     => 'Setting',
                'link1'     => '#',
                'head2'     => 'Standard Value',
                'link2'     => 'setting/standard_value',
                'isi'       => 'setting/standard_value/list',
                'cssadd'    => 'setting/standard_value/cssadd',
                'jsadd'     => 'setting/standard_value/jsadd',
            ];
            $this->load->view('template/wrapper',$data);
        }
    }

    public function load_standard_val(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $kode_kandang = $this->input->post('nama_kandang');
            $tpval = $this->input->post('tpval');
            $datasave = [
                'kode_farm' => $id_farm,
                'kode_kandang' => $kode_kandang,
            ];
            $inidb = $this->umum_model->get('standar_value',$datasave);
            $cekdb = $inidb->num_rows();
            $isidb = $inidb->row_array();
            $setdt = $this->tpvalue2($tpval);
            $iniweek = explode(',',$isidb[$setdt[0]]);
            if($cekdb > 0 and $iniweek[0] != ''){
                $iniweek = explode(',',$isidb[$setdt[0]]);
                $modweek = (count($iniweek) % 7);
                if($modweek > 0){$addweek = 1;}else{$addweek = 0;}
                $totweek = (int)((count($iniweek) - $modweek) / 7) + $addweek;
                $dataini = [
                    'dataweek' => $iniweek,
                    'countweek' => $totweek
                ];

                if(isset($setdt[1])){
                    $dataini['dataweek2'] = explode(',',$isidb[$setdt[1]]);
                }

                echo json_encode(['status' => true, 'dataSet' => $dataini]);
            }else{
                echo json_encode(['status' => false]);
            }
        }
    }

    public function save_standard_val(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');
        $week = $this->input->post('week');
        $tpval = $this->tpvalue2($this->input->post('tpval'));

        $hasilweek = $this->iniweek($week,$tpval);
        
        $datasave = [
            'kode_farm' => $id_farm,
            'kode_kandang' => $kode_kandang,
        ];
        $where = $datasave;
        $datasave[$tpval[0]] = $hasilweek[0];

        if(count($tpval) > 1){
            $datasave[$tpval[1]] = $hasilweek[1];
        }

        $cekdb = $this->umum_model->get('standar_value',$where)->num_rows();
        if($cekdb > 0){
            $this->umum_model->update('standar_value',$datasave,$where);
        }else{
            $this->umum_model->insert('standar_value',$datasave);
        }

        $hasilsave = $this->db->affected_rows();
        if($hasilsave > 0){
            echo json_encode(['status' => true,'message'=>'Data has been saved']);
        }else{
            echo json_encode(['status' => false,'message'=>'Data is not saved']);
        }
    }

    private function iniweek($week,$tpval)
    {
        $hasilweek = [];

        $opweek1 = explode(',',$week[0][0]);
        $nop1 = $opweek1[0];
        if(count($opweek1) > 1){
            for($a1=1; $a1 < count($opweek1); $a1++){
                $nop1 .= '.'.$opweek1[$a1];
            }
        }
        $hasilweek[0] = $nop1;

        if(count($tpval) > 1){
            $opweek12 = explode(',',$week[0][1]);
            $nop12 = $opweek12[0];
            if(count($opweek12) > 1){
                for($a12=1; $a12 < count($opweek12); $a12++){
                    $nop12 .= '.'.$opweek12[$a12];
                }
            }
            $hasilweek[1] = $nop12;
        }

        for ($i=1; $i < count($week); $i++) {
            if($week[$i][0] != ''){
                $opweek = explode(',',$week[$i][0]);
                $nop = $opweek[0];
                if(count($opweek) > 1){
                    for($a=1; $a < count($opweek); $a++){
                        $nop .= '.'.$opweek[$a];
                    }
                }
                $hasilweek[0] .= ','.$nop;    

                if(count($tpval) > 1){
                    $opweek2 = explode(',',$week[$i][1]);
                    $nop2 = $opweek2[0];
                    if(count($opweek2) > 1){
                        for($a2=1; $a2 < count($opweek2); $a2++){
                            $nop2 .= '.'.$opweek2[$a2];
                        }
                    }
                    $hasilweek[1] .= ','.$nop2;
                }
            } 
        }

        return $hasilweek;
    }

    private function tpvalue($data)
    {
        $ini = [
            'temperature' => 'std_temp',
            'humidity' => 'std_humidity',
            'speed_fan' => 'std_fanspeed',
            'wind_speed' => 'std_wind_speed',
            'body_weight' => 'std_body_weight',
            'egg_weight' => 'std_egg_weight',
            'egg_counter' => 'std_egg_counter',
            'water' => 'std_water',
            'feed' => 'std_feed',
            'static_pressure' =>'std_static_press',
            'mortality' => 'std_mortality',
            'selections' => 'std_selections'
        ];

        return isset ($ini[$data]) ? $ini[$data]:'';
    }

    private function tpvalue2($data)
    {
        $ini = [
            'std_temp' => ['std_temp_min','std_temp_max'],
            'std_humidity' => ['std_humidity'],
            'std_fanspeed' => ['std_fanspeed'],
            'std_wind_speed' => ['std_wind_speed'],
            'std_body_weight' => ['std_body_weight_min','std_body_weight_max'],
            'std_egg_weight' => ['std_egg_weight_min','std_egg_weight_max'],
            'std_egg_counter' => ['std_egg_counter'],
            'std_water' => ['std_water_min','std_water_max'],
            'std_feed' => ['std_feed_min','std_feed_max'],
            'std_static_press' => ['std_static_press'],
            'std_mortality' => ['std_mortality'],
            'std_selections' => ['std_selections']
        ];

        return $ini[$data];
    }

    private function setjs($data)
    {
        $ini = [
            'temperature' => 'setting/standard_value/jsadd2',
            'humidity' => 'setting/standard_value/jsadd',
            'speed_fan' => 'setting/standard_value/jsadd',
            'wind_speed' => 'setting/standard_value/jsadd',
            'body_weight' => 'setting/standard_value/jsadd2',
            'egg_weight' => 'setting/standard_value/jsadd2',
            'egg_counter' => 'setting/standard_value/jsadd',
            'water' => 'setting/standard_value/jsadd2',
            'feed' => 'setting/standard_value/jsadd2',
            'static_pressure' =>'setting/standard_value/jsadd',
            'mortality' => 'setting/standard_value/jsadd',
            'selections' => 'setting/standard_value/jsadd'
        ];

        return $ini[$data];
    }

    public function growchange(){
        $this->konfigurasi->cek_url();
        $id_user   = $this->session->userdata('id_user');
        $data1cek = $this->db->query("SELECT id FROM `data_record` WHERE data_record.kode_perusahaan = '".$id_user."' AND data_record.keterangan = 'growchange' LIMIT 1")->num_rows();

        $data = [
            'txthead1'     => 'Growday Change',
            'head1'     => 'Setting',
            'link1'     => '#',
            'head2'     => 'Growday Change',
            'link2'     => 'setting/growchange',
            'isi'       => 'setting/growchange/list',
            'cssadd'    => 'setting/growchange/cssadd',
            'jsadd'     => 'setting/growchange/jsadd',
            'cekdata'   => $data1cek
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function data_change_kandang(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');

            $data1 = $this->db->query("SELECT data_record.kode_kandang As id,data_kandang.nama_kandang As text FROM `data_record` LEFT JOIN data_kandang ON data_record.kode_kandang = data_kandang.id WHERE data_record.kode_perusahaan = '".$id_user."' AND data_record.keterangan = 'growchange' GROUP BY data_record.kode_kandang ORDER BY data_record.kode_kandang ASC")->result();

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

    public function load_growchange(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');

        $house = $this->db->query("SELECT * FROM data_kandang WHERE kode_perusahaan = '".$id_farm."' AND id = '".$kode_kandang."'")->row_array();
        $rawsql = "SELECT periode,growday,date_record,reset_time FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
        $esql1 = $rawsql."AND keterangan = 'ok' ORDER BY growday DESC, date_record DESC LIMIT 1";
        $esql2 = $rawsql."AND keterangan = 'growchange' ORDER BY growday ASC, date_record ASC LIMIT 1";
        $esql3 = $rawsql."AND keterangan = 'growchange' ORDER BY growday DESC, date_record DESC LIMIT 1";

        $inidb1 = $this->db->query($esql1);
        $inidb2 = $this->db->query($esql2);
        $inidb3 = $this->db->query($esql3);

        $cekdb = $inidb2->num_rows();
        $isidb1 = $inidb1->row_array();
        $isidb2 = $inidb2->row_array();
        $isidb3 = $inidb3->row_array();

        $dataini = [];
        $dataini['last_date'] = date_format(date_create($isidb1['date_record']),"H:i:s d F Y");
        $dataini['last_growday'] = $isidb1['growday'];
        $dataini['last_flock'] = $isidb1['periode'];
        $dataini['change_date'] = date_format(date_create($isidb2['date_record']),"H:i:s d F Y");
        $dataini['change_growday'] = $isidb2['growday'];
        $dataini['change_flock'] = $isidb2['periode'];

        $tgl1 = date_create(date_format(date_create($isidb1['date_record']),"Y-m-d H:i:s"));
        $tgl2 = date_create(date_format(date_create($isidb2['date_record']),"Y-m-d H:i:s"));
        $tgl3 = date_create(date_format(date_create($isidb3['date_record']),"Y-m-d H:i:s"));

        $date_in = date_create(date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,$tgl2);
        $difftgl2 = date_diff($date_in,$tgl3);

        $growawal = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");
        $growakhir = (int)$house['star_growday'] + (int)$difftgl2->format("%R%a");

        // $timeakhir = date_format(date_create($isidb3['date_record']),"H");
        // $resetakhir = date_format(date_create($house['reset_time']),"H");

        // if((int)$timeakhir >= (int)$resetakhir){
        //     (int)$growakhir = (int)$growakhir + 1;
        // }

        $dataini['real_date'] = date_format(date_create($isidb2['date_record']),"H:i:s")." ".date_format(date_create($isidb2['date_record']),"d F Y")." to ".date_format(date_create($isidb3['date_record']),"H:i:s d F Y");
        $dataini['real_growday'] = $growawal." to ".$growakhir ;
        $dataini['real_flock'] = $isidb1['periode'];
        $dataini['startgl'] = date_format(date_create($isidb2['date_record']),"Y-m-d");
        $dataini['startime'] = date_format(date_create($isidb2['date_record']),"H:i");
        $dataini['endtgl'] = date_format(date_create($isidb3['date_record']),"Y-m-d");
        $dataini['endtime'] = date_format(date_create($isidb3['date_record']),"H:i");
        $dataini['stargrow'] = $growawal;
        $dataini['endgrow'] = $growakhir;

        if($cekdb > 0){
            echo json_encode(['status' => true, 'dataset' => $dataini]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function load_inputchange(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');
        $startgl = $this->input->post('startgl');
        $endtgl = $this->input->post('endtgl');
        $startime = $this->input->post('startime').':00';
        $endtime = $this->input->post('endtime').':00';

        $house = $this->db->query("SELECT * FROM data_kandang WHERE kode_perusahaan = '".$id_farm."' AND id = '".$kode_kandang."'")->row_array();
        // $rawsql = "SELECT periode,growday,date_record,reset_time FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
        // $esql1 = $rawsql."AND date_record >= '".$startgl." ".$startime."' ORDER BY date_record ASC LIMIT 1";
        // $esql2 = $rawsql."AND date_record <= '".$endtgl." ".$endtime."' ORDER BY date_record DESC LIMIT 1";
        // $inidb1 = $this->db->query($esql1)->row_array();
        // $inidb2 = $this->db->query($esql2)->row_array();
        // $cekdb = $this->db->query($esql1)->num_rows();

        $tgl1 = date_create(date_format(date_create($startgl." ".$startime),"Y-m-d H:i:s"));
        $tgl2 = date_create(date_format(date_create($endtgl." ".$endtime),"Y-m-d H:i:s"));
        $date_in = date_create(date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,$tgl1);
        $difftgl2 = date_diff($date_in,$tgl2);
        $growawal = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");
        $growakhir = (int)$house['star_growday'] + (int)$difftgl2->format("%R%a");
        // $timeakhir = date_format(date_create($inidb2['date_record']),"H");
        // $resetakhir = date_format(date_create($inidb2['reset_time']),"H");

        // if($timeakhir >= $resetakhir){
        //     $growawal = (int)$growawal + 1;
        // }

        $dataini['stargrow'] = strval($growawal);
        $dataini['endgrow'] = strval($growakhir);
        $dataini['startime'] = date_format(date_create($startime),"H:i");
        $dataini['endtime'] = date_format(date_create($endtime),"H:i");

        if($growakhir > $growawal){
            echo json_encode(['status' => true, 'dataset' => $dataini]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function load_inputchangeend(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $kode_kandang = $this->input->post('nama_kandang');
            $startgl = $this->input->post('startgl');
            $stargrow = $this->input->post('stargrow');
            $endtgl = $this->input->post('endtgl');

            $rawsql = "SELECT periode,growday,date_record,reset_time FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
            $esql2 = $rawsql."AND date_record like '".$endtgl."%' AND keterangan = 'growchange' ORDER BY growday DESC, date_record DESC LIMIT 1";
            $esql3 = $rawsql."AND keterangan = 'growchange' ORDER BY growday DESC, date_record DESC LIMIT 1";
            $inidb2 = $this->db->query($esql2)->row_array();
            $inidb3 = $this->db->query($esql3)->row_array();
 
            $difftgl1 = date_diff(date_create($startgl),date_create($endtgl));
            $growawal = (int)$stargrow + (int)$difftgl1->format("%R%a");
            $timeakhir = date_format(date_create($inidb2['date_record']),"H");
            $resetakhir = date_format(date_create($inidb2['reset_time']),"H");

            if($timeakhir >= $resetakhir){
                $growawal = (int)$growawal + 1;
            }

            $dataini['endgrow'] = strval($growawal);
 
            $datesr = date_format(date_create($endtgl),"Y").date_format(date_create($endtgl),"m").date_format(date_create($endtgl),"d")."00";
            $dateend = date_format(date_create($inidb3['date_record']),"Y").date_format(date_create($inidb3['date_record']),"m").date_format(date_create($inidb3['date_record']),"d").date_format(date_create($inidb3['date_record']),"H");

            if($inidb2['date_record'] != ''){
                $dataini['endtime'] = date_format(date_create($inidb2['date_record']),"H:i");
            }else{
                $dataini['endtime'] = date_format(date_create("11:11"),"H:i");
            }

            if((int)$datesr > (int)$dateend){
                echo json_encode(['status' => false]);
            }else{
                echo json_encode(['status' => true, 'dataset' => $dataini]);
            }

        }
    }

    public function save_growchange()
    {
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm      = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');
        $startgl      = $this->input->post('startgl');
        $stargrow     = $this->input->post('stargrow');
        $endtgl       = $this->input->post('endtgl');
        $endgrow      = $this->input->post('endgrow');
        $flock        = $this->input->post('flock');

        $rawsql = "SELECT periode,growday,date_record,reset_time FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
        $esql3 = $rawsql."AND keterangan = 'growchange' ORDER BY growday ASC, date_record ASC LIMIT 1";

        $addwhere = "kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."'";
        $addorder1 = "ORDER BY date_record ASC LIMIT 1";
        $addorder2 = "ORDER BY date_record DESC LIMIT 1";

        $getgrowday1 = "SELECT growday FROM data_record WHERE ".$addwhere." AND date_record like '".$startgl."%' ".$addorder1;
        $gettglawal = "SELECT date_record FROM data_record WHERE ".$addwhere." AND growday = (".$getgrowday1.") ".$addorder1;

        $getgrowday2 = "SELECT growday FROM data_record WHERE ".$addwhere." AND date_record like '".$endtgl."%' ".$addorder2;
        $gettglakhir = "SELECT date_record FROM data_record WHERE ".$addwhere." AND growday = (".$getgrowday2.") ".$addorder2;

        $esqlloop = "SELECT periode,growday,date_record,reset_time FROM data_record WHERE ".$addwhere." AND date_record >= (".$gettglawal.") AND date_record <= (".$gettglakhir.") ORDER BY date_record ASC";

        $inidb2 = $this->db->query($esqlloop)->result();

        $data2 = [];
        $difftglegg = "";

        foreach ($inidb2 as $value) {
            $diff1 = date_create(date_format(date_create($startgl),"Y-m-d"));
            $diff2 = date_create(date_format(date_create($value->date_record),"Y-m-d"));
            $difftgl1 = date_diff($diff1,$diff2);
            $growset = (int)$stargrow + (int)$difftgl1->format("%R%a");

            $jamrecord = date_format(date_create($value->date_record),"H").date_format(date_create($value->date_record),"i").date_format(date_create($value->date_record),"s");
            $jamreset = date_format(date_create($value->reset_time),"H").date_format(date_create($value->reset_time),"i").date_format(date_create($value->reset_time),"s");
            if((int)$jamrecord >= (int)$jamreset){$growset2 = $growset +1;}else{$growset2 = $growset;}

            $data = [];
            $data['growday'] = $growset2;
            $data['periode'] = $flock;
            $data['keterangan'] = 'ok';
            $where = ['date_record' => $value->date_record];

            $this->db->update('data_record',$data,$where);

            if($difftglegg != $value->date_record){
                $dbegg = $this->db->query("SELECT date_record FROM data_eggcounter WHERE ".$addwhere." AND date_record = '".$where['date_record']."' LIMIT 1");
                $cekegg = $dbegg->num_rows();
                $hsegg = $dbegg->row_array();

                if($cekegg > 0){
                    $difftglegg = $hsegg['date_record'];
                    $this->db->update('data_eggcounter',$data,$where);
                }
            }

            $data['date_record'] = $where['date_record'];
            $data2 = $data;
            $data2['reset'] = $jamreset;
        }
        
        $this->db->update('data_kandang',['flock' => $flock],['id' => $kode_kandang,'kode_perusahaan' => $id_farm]);

        $cekdb = $this->db->query("SELECT growday FROM data_record WHERE ".$addwhere." AND keterangan = 'growchange' LIMIT 1")->num_rows();
        $cekdb2 = $this->db->query("SELECT growday FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND keterangan = 'growchange' LIMIT 1")->num_rows();

        $diffsh1 = date_create(date_format(date_create($data2['date_record']),"Y-m-d"));
        $diffsh2 = date_create(date_format(date_create(date("Y-m-d")),"Y-m-d"));
        $difftgl2 = date_diff($diffsh1,$diffsh2);
        $growsetsh = (int)$data2['growday'] + (int)$difftgl2->format("%R%a");
        $jamnow = date_format(date_create(date("H:i:s")),"H").date_format(date_create(date("H:i:s")),"i").date_format(date_create(date("H:i:s")),"s");
        if((int)$jamnow >= (int)$data2['reset']){$growsetsh = $growsetsh +1;}

        $html = '<p style="font-size: 16px">Data has been changed!<br><br>';
        if($cekdb == 0){
            $html .= 'The next step is to change growday data on the controller. The growday data for the current date (<b>'.date_format(date_create(date("Y-m-d")),"d F Y").'</b>) is <b>'.$growsetsh.'</b>.';
            if($cekdb2 == 0){
                $html .= '</b>.<br><br>After closed, this form will automatically switch pages.';
            }
        }else{
            $html .= '<span style="font-size: 15px"><b>There are still incorrect data</b></span>. <br>Please change the growday data again or you can change the flock data to create a new flock data.';
        }
        $html .= '</p>';
        
        echo json_encode(['status' => true,'message' => $html]);
     }

    public function nfd(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        $id_farm  = $this->session->userdata('id_user');

        $dt_kandang = $this->db->query("SELECT id,nama_kandang,kode_perusahaan FROM data_kandang WHERE kode_perusahaan = '".$id_farm."'")->result();
        $countnf = 0;
        $html = "";
        $suhuatas = 1;
        $suhubawah = -2;

        foreach ($dt_kandang as $value) {
            $where = [
                "kode_kandang" => $value->id,
                "kode_perusahaan" => $value->kode_perusahaan,
            ];
            $dbrealtime = $this->umum_model->get("data_realtime",$where);
            $dbrealtimer = $dbrealtime->row_array();
            $suhunow = floatval($dbrealtimer['avg_temp']);
            $suhureq = floatval($dbrealtimer['req_temp']);
            $difsuhu = $suhunow - $suhureq;

            $img = base_url()."assets/icon_software_ansell/data1.png";
            $nama_kandang = $value->nama_kandang;

            if($difsuhu > $suhuatas){
                $countnf = $countnf + 1;
                $statussuhu = "higher";
                $iconarrow = 'fa-arrow-up';
                $colorarrow = '#ce3232';
            }else if($difsuhu < $suhubawah){
                $countnf = $countnf + 1;
                $statussuhu = "lower";
                $iconarrow = 'fa-arrow-down';
                $colorarrow = '#258fff';
            }

            if($difsuhu < $suhubawah OR $difsuhu > $suhuatas){
                $html .= '<li><label><img src="';
                $html .= $img;
                $html .= '" style="height:inherit;width:25px;"><span style="font-weight:lighter;">Temperature</span> (<span>';
                $html .= $nama_kandang;
                $html .= '</span>)</label>';
                $html .= '<p style="padding:0px 10px; 0px"><i class="fa ';
                $html .= $iconarrow;
                $html .= '" style="color:';
                $html .= $colorarrow;
                $html .= ';"></i>&nbsp;Current data is ';
                $html .= $suhunow;
                $html .= "&nbsp;";
                $html .= $statussuhu;
                $html .= '&nbsp;than&nbsp;';
                $html .= $suhureq;
                $html .= '<br><br>';
                $html .= '<span style="float:right;">';
                $html .= $dbrealtimer['date_create'];
                $html .= '</span></p><hr></li>';    
            }
        }

        if($countnf != 0){
            echo json_encode(['status'=>true,'nfd'=>$html,'nfc'=>$countnf]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }
}