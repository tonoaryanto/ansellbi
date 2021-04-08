<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Growchange extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    public function index(){
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
        $dataini['change_date'] = date_format(date_create($isidb2['date_record']),"H:i:s d F Y")." to ".date_format(date_create($isidb3['date_record']),"H:i:s d F Y");
        $dataini['change_growday'] = $isidb2['growday']." to ".$isidb3['growday'];
        $dataini['change_flock'] = $isidb2['periode'];

        $tgl1 = date_create(date_format(date_create($isidb1['date_record']),"Y-m-d H:i:s"));
        $tgl2 = date_create(date_format(date_create($isidb2['date_record']),"Y-m-d H:i:s"));
        $tgl3 = date_create(date_format(date_create($isidb3['date_record']),"Y-m-d H:i:s"));

        $date_in = date_create(date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,$tgl2);
        $difftgl2 = date_diff($date_in,$tgl3);

        $growawal = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");
        $growakhir = (int)$house['star_growday'] + (int)$difftgl2->format("%R%a");

        $dataini['real_date'] = date_format(date_create($isidb2['date_record']),"H:i:s")." ".date_format(date_create($isidb2['date_record']),"d F Y")." to ".date_format(date_create($isidb3['date_record']),"H:i:s d F Y");
        $dataini['real_growday'] = $growawal." to ".$growakhir ;
        $dataini['real_flock'] = $house['flock'];
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
        $tgl1 = date_create(date_format(date_create($startgl." ".$startime),"Y-m-d H:i:s"));
        $tgl2 = date_create(date_format(date_create($endtgl." ".$endtime),"Y-m-d H:i:s"));
        $date_in = date_create(date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,$tgl1);
        $difftgl2 = date_diff($date_in,$tgl2);
        $growawal = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");
        $growakhir = (int)$house['star_growday'] + (int)$difftgl2->format("%R%a");

        $dataini['stargrow'] = strval($growawal);
        $dataini['endgrow'] = strval($growakhir);
        $dataini['startime'] = date_format(date_create($startime),"H:i");
        $dataini['endtime'] = date_format(date_create($endtime),"H:i");

        $cektgl1  = date_format(date_create($startgl." ".$startime),"Y");
        $cektgl1 .= date_format(date_create($startgl." ".$startime),"m");
        $cektgl1 .= date_format(date_create($startgl." ".$startime),"d");
        $cektgl1 .= date_format(date_create($startgl." ".$startime),"H");
        $cektgl1 .= date_format(date_create($startgl." ".$startime),"i");
        $cektgl1 .= date_format(date_create($startgl." ".$startime),"s");

        $cektgl2  = date_format(date_create($endtgl." ".$endtime),"Y");
        $cektgl2 .= date_format(date_create($endtgl." ".$endtime),"m");
        $cektgl2 .= date_format(date_create($endtgl." ".$endtime),"d");
        $cektgl2 .= date_format(date_create($endtgl." ".$endtime),"H");
        $cektgl2 .= date_format(date_create($endtgl." ".$endtime),"i");
        $cektgl2 .= date_format(date_create($endtgl." ".$endtime),"s");

        $difftgl3 = (int)($cektgl2) - (int)($cektgl1);
        $batasgrow = 0;
        if($growawal < (int)$house['star_growday'] or $growakhir < (int)$house['star_growday']){$batasgrow = 1;}

        if($difftgl3 > 0 and $batasgrow == 0){
            echo json_encode(['status' => true, 'dataset' => $dataini]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function save_growchange()
    {
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm      = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');
        $startgl      = $this->input->post('startgl');
        $startime      = $this->input->post('startime').":00";
        $stargrow     = $this->input->post('stargrow');
        $endtgl       = $this->input->post('endtgl');
        $endtime       = $this->input->post('endtime').":00";
        $endgrow      = $this->input->post('endgrow');
        $flock        = $this->input->post('flock');

        $addwhere = "kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."'";
        $settglawal = date_format(date_create($startgl." ".$startime),"Y-m-d H:i:s");
        $settglakhir = date_format(date_create($endtgl." ".$endtime),"Y-m-d H:i:s");

        $esqlloop = "SELECT id,periode,growday,date_record,reset_time FROM data_record WHERE ".$addwhere." AND date_record >= '".$settglawal."' AND date_record <= '".$settglakhir."' ORDER BY date_record ASC";
        $inidb2 = $this->db->query($esqlloop)->result();

        $house = $this->db->query("SELECT * FROM data_kandang WHERE kode_perusahaan = '".$id_farm."' AND id = '".$kode_kandang."'")->row_array();
        $date_in = date_create(date_format(date_create($house['date_in']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $data2 = [];
        $difftglegg = "";

        foreach ($inidb2 as $value) {
            $diff2 = date_create(date_format(date_create($value->date_record),"Y-m-d H:i:s"));
            $difftgl1 = date_diff($date_in,$diff2);
            $growset = (int)$house['star_growday'] + (int)$difftgl1->format("%R%a");

            $jamreset = date_format(date_create($value->reset_time),"H").date_format(date_create($value->reset_time),"i").date_format(date_create($value->reset_time),"s");

            $data = [];
            $data['growday'] = $growset;
            $data['periode'] = $flock;
            $data['keterangan'] = 'ok';
            $where = ['id' => $value->id];

            $this->db->update('data_record',$data,$where);

            $eggtglreset = date_format(date_create($value->date_record),"Y-m-d");

            if($difftglegg != $eggtglreset){
                $dbegg = $this->db->query("SELECT date_record FROM data_eggcounter WHERE ".$addwhere." AND date_record = '".$eggtglreset."' LIMIT 1");
                $cekegg = $dbegg->num_rows();
                $hsegg = $dbegg->row_array();

                if($cekegg > 0){
                    $difftglegg = date_format(date_create($hsegg['date_record']),"Y-m-d");
                    $this->db->update('data_eggcounter',$data,['date_record' => $eggtglreset]);
                }
            }

            $data['date_record'] = $value->date_record;
            $data2 = $data;
            $data2['reset'] = $jamreset;

        }
        
        $this->db->update('data_kandang',['flock' => $flock],['id' => $kode_kandang,'kode_perusahaan' => $id_farm]);

        $cekdb = $this->db->query("SELECT growday FROM data_record WHERE ".$addwhere." AND keterangan = 'growchange' LIMIT 1")->num_rows();
        $cekdb2 = $this->db->query("SELECT growday FROM data_record WHERE kode_perusahaan = '".$id_farm."' AND keterangan = 'growchange' LIMIT 1")->num_rows();

        $diffsh2 = date_create(date_format(date_create(date("Y-m-d H:i:s")),"Y-m-d H:i:s"));
        $difftgl2 = date_diff($date_in,$diffsh2);
        $growsetsh = (int)$house['star_growday'] + (int)$difftgl2->format("%R%a");

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
}