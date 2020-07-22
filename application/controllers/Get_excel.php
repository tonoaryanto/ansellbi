<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Get_excel extends CI_Controller {

    function __construct(){
        parent::__construct();
		$this->load->library('datatables');
    }

    public function index(){
        $this->session->unset_userdata('kode_excel');
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Upload Data House',
            'head1'     => 'Upload',
            'link1'     => '#',
            'head2'     => 'Upload Data House',
            'link2'     => 'get_excel',
            'isi' => 'get_excel/list',
            'cssadd' => 'get_excel/cssadd',
            'jsadd' => 'get_excel/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function alarm(){
        $this->session->unset_userdata('kode_excel');
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Upload Data Alarm',
            'head1'     => 'Upload',
            'link1'     => '#',
            'head2'     => 'Upload Data Alarm',
            'link2'     => 'get_excel/alarm',
            'isi' => 'get_excel/alarm/list',
            'cssadd' => 'get_excel/alarm/cssadd',
            'jsadd' => 'get_excel/alarm/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function open_file(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $kode_perusahaan = $this->session->userdata('id_user');
            $value_periode = $this->input->post('periode');
            $id_kandang = $this->input->post('select_kandang');
    
            $isigrowlast = $this->db->query("SELECT grow_value FROM image2 WHERE kode_perusahaan = '".$kode_perusahaan."' AND periode = '".$value_periode."' AND kode_kandang = '".$id_kandang."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];

            $arr_file = explode('.', $_FILES['userfile']['name']);
            $nama_file = explode('_', $arr_file[0]);
            if ($nama_file[0] != 'history' and $nama_file[0] != 'HISTORY' and $nama_file[0] != 'History') {$datatrue = 0;}else{$datatrue = 1;}

            if ($datatrue == 1){
                //membuat data kandang

                $cek_periode = $this->db->query("SELECT id FROM periode WHERE kode_perusahaan = '".$this->session->userdata('id_user')."' AND value_periode = '".$value_periode."'")->num_rows();

                if ($cek_periode == 0) {
                    $this->umum_model->insert('periode',[
                        'value_periode' => $value_periode,
                        'kode_perusahaan' => $this->session->userdata('id_user'),
                    ]);
                }

                $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                $extension = end($arr_file);
                
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                
                $spreadsheet = $reader->load($_FILES['userfile']['tmp_name']);
                    
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                //Menentukan Nama Data
                $data1 = [];
                for ($i=0; $i < count($sheetData); $i++) { 
                    if($sheetData[$i][0] == 'Name'){
                        if (explode('_',$sheetData[$i][1])[0] != '21783' AND explode('_',$sheetData[$i][1])[0] != '23489') {
                        $data['nomor'] = $i;
                        $data['nama'] = explode('_',$sheetData[$i][1])[0];
                        $data['kode_data'] = $sheetData[$i][2];
                        $data['tanggal_dibuat'] = $sheetData[$i][3];
                        $data['jam_dibuat'] = $sheetData[$i][4];
                        $data['value_data'] = $sheetData[$i][5];
                        $data['kategori'] = explode('_',$sheetData[$i][1])[1] . '_' . explode('_',$sheetData[$i][1])[2];
                        $data1[] = $data;
                        }
                    }
                }

                //Menentukan Jumlah Data PerNama
                $data2 = [];
                for ($i=0; $i < count($data1); $i++) { 
                        $data['nomor'] = $data1[$i]['nomor'] + 1;
                        if($i == (count($data1) - 1)){
                            $data['nomor_max'] = count($sheetData);
                        }else{
                            $data['nomor_max'] = $data1[((int)$i + 1)]['nomor'];
                        }
                        $data['nama'] = $data1[$i]['nama'];
                        $data['kode_data'] = $data1[$i]['kode_data'];
                        $data['tanggal_dibuat'] = $data1[$i]['tanggal_dibuat'];
                        $data['jam_dibuat'] = $data1[$i]['jam_dibuat'];
                        $data['value_data'] = $data1[$i]['value_data'];
                        $data['kategori'] = $data1[$i]['kategori'];
                        $data2[] = $data;
                }
                $data1 = [];
                
                //Menggabungkan Data dan isi
                $isiiddata = [];
                $cekiddata = $this->umum_model->get('kode_data',"aktif = 'y'")->result();
                $nm = 0;
                foreach ($cekiddata as $valueid) {
                    $isiiddata[] = $valueid->kode_data;
                    $nm = $nm +1;
                }
                $data3 = [];
                $message = "";
                for ($i=0; $i < count($data2); $i++) {
                    for ($j=$data2[$i]['nomor']; $j < $data2[$i]['nomor_max']; $j++) {
                        if(str_replace(" ","",$sheetData[$j][0]) != 'EOF'){
                            if(str_replace(" ","",$sheetData[$j][0]) == 'H'){
                                for ($ids=0; $ids < count($isiiddata); $ids++) { 
                                    if($data2[$i]['nama'] != $isiiddata[$ids]){
                                    }else{
                                        if(str_replace(" ","",$sheetData[$j][3]) > $isigrowlast){
                                            if($data2[$i]['nama'] == '4096' OR $data2[$i]['nama'] == '1826' OR $data2[$i]['nama'] == '7098' OR $data2[$i]['nama'] == '7099' OR $data2[$i]['nama'] == '7100' OR $data2[$i]['nama'] == '7101' OR $data2[$i]['nama'] == '7197' OR $data2[$i]['nama'] == '7198' OR $data2[$i]['nama'] == '7199' OR $data2[$i]['nama'] == '7200' OR $data2[$i]['nama'] == '7201' OR $data2[$i]['nama'] == '7202' OR $data2[$i]['nama'] == '7203' OR $data2[$i]['nama'] == '7218' OR $data2[$i]['nama'] == '3002' OR $data2[$i]['nama'] == '3003' OR $data2[$i]['nama'] == '3004' OR $data2[$i]['nama'] == '3005' OR $data2[$i]['nama'] == '64760' OR $data2[$i]['nama'] == '62001' OR $data2[$i]['nama'] == '62002'){
                                                $isivalue = number_format(((int)str_replace(" ","",$sheetData[$j][4]) / 10), 1, '.', '');
                                            }else{
                                                $isivalue = str_replace(" ","",$sheetData[$j][4]);
                                            }
            
                                            $value['kategori']  = $data2[$i]['kategori'];
                                            $value['nama_data']  = $data2[$i]['nama'];
                                            $value['kode_data']  = $data2[$i]['kode_data'];
                                            $value['tanggal_data']  = $data2[$i]['tanggal_dibuat'];
                                            $value['jam_data']  = $data2[$i]['jam_dibuat'];
                                            $value['isi_data']  = $data2[$i]['value_data'];
                                            $value['ket_value']  = str_replace(" ","",$sheetData[$j][0]);
                                            $value['tanggal_value']  = str_replace(" ","",$sheetData[$j][1]);
                                            $value['jam_value']  = str_replace(" ","",$sheetData[$j][2]);
                                            $value['grow_value'] = str_replace(" ","",$sheetData[$j][3]);
                                            $value['isi_value'] = $isivalue;
                                            $value['kode_perusahaan'] = $this->session->userdata('id_user');
                                            $value['kode_kandang'] = $id_kandang;
                                            $value['periode'] = $value_periode;
                                            $data3[] = $value;
                                            if($data2[$i]['kategori'] != 'DAY_1'){
                                            $message .= "&#10;- Data (" . $data2[$i]['kategori'];
                                            $message .= ", " . $data2[$i]['nama'];
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][0]);
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][1]);
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][2]);
                                            $message .= ") - Berhasil";
                                            }
                                        }else{
                                            if($data2[$i]['kategori'] != 'DAY_1'){
                                            $message .= "&#10;- Data (" . $data2[$i]['kategori'];
                                            $message .= ", " . $data2[$i]['nama'];
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][0]);
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][1]);
                                            $message .= ", " . str_replace(" ","",$sheetData[$j][2]);
                                            $message .= ") - Sudah ada";                
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $data2 = [];
                $message .= ' &#10;- '.'Selesai . . .';

                if(count($data3) > 0){
                $this->db->insert_batch('image2', $data3);
                $statusdt = $this->db->affected_rows();
                }else{
                $statusdt = 0;
                }
                
                $this->session->set_userdata(['optimizedata' => '1']);

                if($statusdt >= 1){
                echo json_encode(['status'=>true, 'datamessage'=>$message]);
                }else{
                echo json_encode(['status'=>false, 'datamessage'=>$message,'message'=>'<p style="font-size: 14px">Data Sudah Ada!</p>']);
                }

            }else{
                echo json_encode(['status'=>false, 'message'=>'<p style="font-size: 14px">Mohon hanya memasukan file Farm anda dan dengan format yang benar!<br>Contoh : history_[nama farm]_[kode farm]_ENG.csv</p>']);
            }
        }
    }

    public function reset_data(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');
            $kode_kandang = base64_decode($this->input->post('value1'));
            $value2 = base64_decode($this->input->post('value2'));
            $value3 = base64_decode($this->input->post('value3'));

            $datak = $this->umum_model->get('data_kandang',['id' => $kode_kandang])->row_array();

            $dataini = [
                'id_user'         => $id_user,
                'kode_kandang'    => $kode_kandang,
                'nama_penanggung' => $value2,
            ];
/*
            if ($datak['keterangan'] == 'history_alarm') {
                $this->umum_model->delete('history_alarm',['id_user' => $id_user,'kode_kandang' => $kode_kandang]);
                $test2 = $this->db->affected_rows();
                if($test2 >= 1){
                    if ($test2 >= 1) {$addtext2 = 'Riwayat Alarm (direset)';}else{$addtext2 = 'Riwayat Alarm (dtidak direset)';}
                    $dataini['keterangan'] = $value3.' &#10;'.$addtext2;
                    $this->umum_model->insert('log_reset',$dataini);
                    $this->umum_model->delete('data_kandang',['id' => $kode_kandang]);
                    echo json_encode(['status'=>true,'data'=>$test2]);
                }else{
                    echo json_encode(['status'=>false,'data'=>$test2]);
                }
            }

            if ($datak['keterangan'] == 'history_house') {
                $this->umum_model->delete('image2',['kode_perusahaan' => $id_user,'kode_kandang' => $kode_kandang]);
                $test = $this->db->affected_rows();
                if($test >= 1){
                    if ($test >= 1) {$addtext1 = 'Riwayat House (direset)';}else{$addtext1 = 'Riwayat House (dtidak direset)';}
                    $dataini['keterangan'] = $value3.' &#10;'.$addtext1;
                    $this->umum_model->insert('log_reset',$dataini);
                    $this->umum_model->delete('data_kandang',['id' => $kode_kandang]);
                    echo json_encode(['status'=>true,'data'=>$test]);
                }else{
                    echo json_encode(['status'=>false,'data'=>$test]);
                }
            }
*/            
        }
    }

    public function open_file_alarm(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{

            $arr_file = explode('.', $_FILES['userfile']['name']);
            $nama_file = explode('_', $arr_file[0]);
            if ($nama_file[0] != 'AlarmHistory' and $nama_file[0] != 'ALARMHISTORY' and $nama_file[0] != 'alarmhistory') {$datatrue = 0;}else{$datatrue = 1;}

            if ($datatrue == 1){

                $value_periode = $this->input->post('periode');

                $cek_periode = $this->db->query("SELECT id FROM periode WHERE kode_perusahaan = '".$this->session->userdata('id_user')."' AND value_periode = '".$value_periode."'")->num_rows();

                if ($cek_periode == 0) {
                    $this->umum_model->insert('periode',[
                        'value_periode' => $value_periode,
                        'kode_perusahaan' => $this->session->userdata('id_user'),
                    ]);
                }

                $id_kandang = $this->input->post('select_kandang');

                $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

                $extension = end($arr_file);
                
                if('csv' == $extension) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }
                
                $spreadsheet = $reader->load($_FILES['userfile']['tmp_name']);
                    
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                //Menentukan Data
                $data1 = [];
                $listno = 0;
                for ($i=0; $i < count($sheetData); $i++) { 
                    if(explode(',', $sheetData[$i][0])[0] == 'EventCount'){
                        $data1['dari'] = $i + 1;
                        $data1['sampai'] = $i + (int)explode(',', $sheetData[$i][0])[1] + 1;
                        break;
                    }
                }

                //Menentukan Isi Data
                $data2 = [];
                for ($j=$data1['dari']; $j < $data1['sampai']; $j++) { 
                    $isi1 = explode(',', $sheetData[$j][0]);
                    $isi2 = $sheetData[$j][1];
                    $isi3 = explode(',', $sheetData[$j][2]);

                    $idata['value1']  = $this->session->userdata('id_user');
                    $idata['value2']  = $id_kandang;
                    $idata['value3']  = $isi3[1];
                    $idata['value4']  = $isi3[2];
                    $idata['value5']  = $isi1[0];
                    $idata['value6']  = $isi1[1].':'.$isi2.':'.$isi3[0];
                    $idata['value7']  = $isi3[8];
                    $idata['value8']  = $isi3[11];
                    $idata['value9']  = $isi3[5];
                    $idata['value10'] = $isi3[12];
                    $idata['value11'] = $isi3[6];
                    $idata['value12'] = $isi3[16];
                    $idata['value13'] = $isi3[17];
                    $idata['value14'] = $isi3[4];
                    $idata['value15'] = $isi3[13];
                    $idata['value16'] = $isi3[14];
                    $idata['value17'] = $isi3[7];
                    $idata['value18'] = $isi3[15];
                    $idata['value19'] = $isi3[3];
                    $idata['value20'] = $isi3[9];
                    $idata['value21'] = $isi3[10];
                    $idata['value22'] = $value_periode;
                    $data2[] = $idata;
                }

                $kode = $this->umum_model->kode_acak();
                $this->session->set_userdata(['kode_excel'=>$kode]);

                $hasilini = $this->save_data_alarm($data2,count($data2),$kode);
                if ($hasilini['status'] == 1) {
                echo json_encode(['status'=>true, 'datamessage'=>$hasilini['message']]);
                }else{
                echo json_encode(['status'=>false, 'datamessage'=>$hasilini['message'],'message'=>'<p style="font-size: 14px">Terjadi Kesalahan saat memproses data!</p>']);
                }
            }else{
                echo json_encode(['status'=>false, 'message'=>'<p style="font-size: 14px">Mohon hanya memasukan file Farm anda dan dengan format yang benar!<br>Contoh : AlarmHistory_[nama farm]_[kode farm]_ENG.csv</p>']);
            }
        }
    }

    private function save_data_alarm($isidata,$bnykdata,$kodex){
        $data_kode = $this->session->userdata('kode_excel');

        $message = "";
        if ($data_kode == '') {$data_kode = $this->umum_model->kode_acak();}
        for ($i=0; $i < $bnykdata; $i++) {
            if ($kodex == $data_kode) {
                $cekdata = $this->umum_model->get('history_alarm',[
                    'id_user' => $isidata[$i]['value1'],
                    'kode_kandang' => $isidata[$i]['value2'],
                    'type' => $isidata[$i]['value3'],
                    'alarm_param' => $isidata[$i]['value4'],
                    'tanggal' => $isidata[$i]['value5'],
                    'jam' => $isidata[$i]['value6'],
                    'growday' => $isidata[$i]['value7'],
                    'data5' => $isidata[$i]['value19']
                ])->num_rows();

                if ($cekdata < 1) {
                    $idata['id_user'] = $isidata[$i]['value1'];
                    $idata['kode_kandang'] = $isidata[$i]['value2'];
                    $idata['type'] = $isidata[$i]['value3'];
                    $idata['alarm_param'] = $isidata[$i]['value4'];
                    $idata['tanggal'] = $isidata[$i]['value5'];
                    $idata['jam'] = $isidata[$i]['value6'];
                    $idata['growday'] = $isidata[$i]['value7'];
                    $idata['alarm1'] = $isidata[$i]['value8'];
                    $idata['disable1'] = $isidata[$i]['value9'];
                    $idata['alarm2'] = $isidata[$i]['value10'];
                    $idata['disable2'] = $isidata[$i]['value11'];
                    $idata['alarm3'] = $isidata[$i]['value12'];
                    $idata['disable3'] = $isidata[$i]['value13'];
                    $idata['req_temp'] = (int)$isidata[$i]['value14'] / 10;
                    $idata['in_temp'] = (int)$isidata[$i]['value15'] / 10;
                    $idata['humidity'] = $isidata[$i]['value16'];
                    $idata['weight'] = $isidata[$i]['value17'];
                    $idata['state'] = $isidata[$i]['value18'];
                    $idata['data5'] = $isidata[$i]['value19'];
                    $idata['data11'] = $isidata[$i]['value20'];
                    $idata['data12'] = $isidata[$i]['value21'];
                    $idata['periode'] = $isidata[$i]['value22'];
                    $this->umum_model->insert('history_alarm', $idata);

                    $message .= "&#10;- Data (" . $isidata[$i]['value3'];
                    $message .= ", " . $isidata[$i]['value4'];
                    $message .= ", " . $isidata[$i]['value5'];
                    $message .= ", " . $isidata[$i]['value6'];
                    $message .= ", " . $isidata[$i]['value19'];
                    $message .= ") - Berhasil";
                }else{
                    $message .= "&#10;- Data (" . $isidata[$i]['value3'];
                    $message .= ", " . $isidata[$i]['value4'];
                    $message .= ", " . $isidata[$i]['value5'];
                    $message .= ", " . $isidata[$i]['value6'];
                    $message .= ", " . $isidata[$i]['value19'];
                    $message .= ") - Sudah ada";                
                }

                //Hapus Session
                if( (int)$i == ((int)$bnykdata - 1)){
                    $this->session->unset_userdata('kode_excel');
                    $message .= "&#10;- Selesai . . .";
                    $sstatus = 1;
                }
            }else{
                $sstatus = 0;
            }
        }
        return ['status' => $sstatus,'message'=>$message];
    }

    public function data_select_kandang(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');

            $this->db->select('id,nama_kandang AS text');
            $this->db->from('data_kandang');
            $this->db->where(['kode_perusahaan'=>$id_user]);
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

    public function dtkandang()
    {
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');
            $fil=$this->input->get('search')?$this->input->get('search'):NULL;
            $this->db->select(['id','nama_kandang']);
            $this->db->from('data_kandang');
            $this->db->where([
                                'kode_perusahaan'=>$id_user
                            ]);
            $this->db->like('nama_kandang',$fil,'both');
            $this->db->limit(50);
            $db =$this->db->get();
            $data = [];
            foreach ($db->result() as $d) {
                $data[]= array(
                    'id'   => $d->id,
                    'text' => $d->nama_kandang,
                 );
            }
            $send= $data;
            if($fil == NULL){
                 echo json_encode($send);
            }else{
                 echo json_encode($send);
            }
        }
    }

    public function add_new_kandang()
    {
        $nama_kandang = $this->input->post('value1');

        $last_id_kandang = $this->db->query("SELECT id FROM data_kandang ORDER BY id DESC")->row_array()['id'];
        if ($last_id_kandang == '') {
            $id_kandang = 1;
        }else{
            $id_kandang = (int)$last_id_kandang + 1;
        }

        $this->umum_model->insert('data_kandang',[
            'id' => $id_kandang,
            'nama_kandang' => $nama_kandang,
            'kode_perusahaan' => $this->session->userdata('id_user'),
        ]);
        if ($this->db->affected_rows() == 1) {
         echo json_encode(['status'=>true]);
        }else{
         echo json_encode(['status'=>false,"message"=>'<p style="font-size: 14px">Terjadi Kesalahan.</p>']);
        }
    }

    public function last_data()
    {
        $kode_perusahaan = $this->session->userdata('id_user');
        $periode = $this->input->get('value1');
        $kandang = $this->input->get('value2');

        $isi = $this->db->query("SELECT periode,grow_value FROM image2 WHERE kode_perusahaan = '".$kode_perusahaan."' AND periode = '".$periode."' AND kode_kandang = '".$kandang."' ORDER BY grow_value DESC LIMIT 1")->row_array();

        if ($isi['periode'] == '')    {$isip = 'kosong';}else{$isip = $isi['periode'];}
        if ($isi['grow_value'] == '') {$isig = 'kosong';}else{$isig = $isi['grow_value'];}

        echo json_encode(['status'=>true,'periode'=>$isip,'growday'=>$isig]);
    }

    public function last_data_alarm()
    {
        $kode_perusahaan = $this->session->userdata('id_user');
        $periode = $this->input->get('value1');
        $kandang = $this->input->get('value2');

        $isi = $this->db->query("SELECT periode,growday FROM history_alarm WHERE id_user = '".$kode_perusahaan."' AND periode = '".$periode."' AND kode_kandang = '".$kandang."' ORDER BY growday DESC LIMIT 1")->row_array();

        if ($isi['periode'] == '')    {$isip = 'kosong';}else{$isip = $isi['periode'];}
        if ($isi['growday'] == '') {$isig = 'kosong';}else{$isig = $isi['growday'];}

        echo json_encode(['status'=>true,'periode'=>$isip,'growday'=>$isig]);
    }

}