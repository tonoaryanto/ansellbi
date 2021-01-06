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

        $data = [
            'txthead1'     => 'Growday Change',
            'head1'     => 'Setting',
            'link1'     => '#',
            'head2'     => 'Growday Change',
            'link2'     => 'setting/growchange',
            'isi'       => 'setting/growchange/list',
            'cssadd'    => 'setting/growchange/cssadd',
            'jsadd'     => 'setting/growchange/jsadd',
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
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $kode_kandang = $this->input->post('nama_kandang');

            $rawsql = "SELECT periode,growday,date_record FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
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

            $tgl1 = date_create("2020-12-28");//date_create($isidb1['date_record']);
            $tgl2 = date_create("2020-12-29");//date_create($isidb2['date_record']);
            $tgl3 = date_create("2021-01-04");//date_create($isidb3['date_record']);

            $difftgl1 = date_diff($tgl1,$tgl2);
            $difftgl2 = date_diff($tgl1,$tgl3);

            $growawal = (int)$isidb1['growday'] + (int)$difftgl1->format("%R%a");
            $growakhir = (int)$isidb1['growday'] + (int)$difftgl2->format("%R%a");

            $dataini['real_date'] = date_format(date_create($isidb2['date_record']),"H:i:s d F Y")." to ".date_format(date_create($isidb3['date_record']),"H:i:s d F Y");
            $dataini['real_growday'] = $growawal." to ".$growakhir ;
            $dataini['real_flock'] = $isidb1['periode'];
            $dataini['startgl'] = date_format(date_create($isidb2['date_record']),"Y-m-d");
            $dataini['startime'] = date_format(date_create($isidb2['date_record']),"H:i");
            $dataini['stargrow'] = $growawal;
            $dataini['endtgl'] = date_format(date_create($isidb3['date_record']),"Y-m-d");
            $dataini['endtime'] = date_format(date_create($isidb3['date_record']),"H:i");
            $dataini['endgrow'] = $growakhir;

            if($cekdb > 0){
                echo json_encode(['status' => true, 'dataset' => $dataini]);
            }else{
                echo json_encode(['status' => false]);
            }
        }
    }

    public function load_inputchange(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $kode_kandang = $this->input->post('nama_kandang');
            $startgl = $this->input->post('startgl');
            $endtgl = $this->input->post('endtgl');
            $rawsql = "SELECT periode,growday,date_record FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' ";
            $esql1 = $rawsql."AND date_record = '".$startgl."' AND keterangan = 'ok' ORDER BY growday DESC LIMIT 1";
            $inidb1 = $this->db->query($esql1)->row_array();
            $inidb1['date_record'];
        }
    }

}