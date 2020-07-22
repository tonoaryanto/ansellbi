<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Histori_house extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('grafik_model');
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        redirect(base_url('histori_house/day'));
    }

    public function day(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Histori House ( DAY )',
            'head1'     => 'Histori House',
            'link1'     => '#',
            'head2'     => '<b>Day</b>',
            'link2'     => 'histori_house/day',
            'isi'       => 'histori_house/day/list',
            'cssadd'    => 'histori_house/day/cssadd',
            'jsadd'     => 'histori_house/day/jsadd',
        ];
        $this->load->view('template/view_data/wrapper',$data);
    }

    public function hour(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Histori House ( HOUR )',
            'head1'     => 'Histori House',
            'link1'     => '#',
            'head2'     => '<b>Hour</b>',
            'link2'     => 'histori_house/hour',
            'isi'       => 'histori_house/hour/list',
            'cssadd'    => 'histori_house/hour/cssadd',
            'jsadd'     => 'histori_house/hour/jsadd',
        ];
        $this->load->view('template/view_data/wrapper',$data);
    }

    public function datajson(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2');
            $fil3 = $this->input->post('value3');
            $id_user   = $this->session->userdata('id_user');
            $fildari   = $this->input->post('value4');
            $filsampai = $this->input->post('value5');
            $filhour = $this->input->post('value6');
            $filperiode = $this->input->post('value7');

            $where_kodep = "kode_perusahaan = '".$id_user."'";

            if($fil1 == 'DAY_1'){
                if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
                    $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
                    $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
                }

                $esql  = "SELECT grow_value,isi_value FROM `image2` ";
                $esql .= "WHERE ".$where_kodep." ";
                $esql .= "AND kode_kandang = '".$fil3."' ";
                $esql .= "AND nama_data = '".$fil2."' ";
                $esql .= "AND kategori = '".$fil1."' ";
                $esql .= "AND periode = '".$filperiode."' ";
                $esql .= "AND grow_value BETWEEN '".$fildari."' AND '".$filsampai."' ";
                $esql .= "ORDER BY grow_value ASC";
            }
            if($fil1 == 'HOUR_1'){
                if($filhour == '-1'){
                    $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
                }
                $esql  = "SELECT LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS grow_value,isi_value FROM `image2` ";
                $esql .= "WHERE ".$where_kodep." ";
                $esql .= "AND kode_kandang = '".$fil3."' ";
                $esql .= "AND nama_data = '".$fil2."' ";
                $esql .= "AND kategori = '".$fil1."' ";
                $esql .= "AND periode = '".$filperiode."' ";
                $esql .= "AND grow_value = '".$filhour."' ";
                $esql .= "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";
            }

            $image2_raw = $this->db->query($esql);

            //SET LABEL
            if($fil1 == 'DAY_1'){$addlabel = ' : Grow Day '.$fildari.' s/d '.$filsampai.' ';}
            if($fil1 == 'HOUR_1'){$addlabel = ' : Grow Day '.$filhour.' ';}
            $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
            $glabel = $label.$addlabel;

            $cek_image2 = $image2_raw->num_rows();
            if($cek_image2 > 1){
                $image2 = $image2_raw->result();
                $data2 = [];
                foreach ($image2 as $value) {
                    if($fil1 == 'DAY_1'){
                    $adata[]  = $value->grow_value;
                    }
                    if($fil1 == 'HOUR_1'){
                    $adata[]  = $value->grow_value.':00';
                    }
                }
                $labelgf = $adata;

                foreach ($image2 as $value2) {
                    $bdata[] = $value2->isi_value;
                }
                $adata2 = $bdata;

                if($fil1 == 'DAY_1'){
                    echo json_encode(['status'=>true,'labelgf'=>$labelgf,'data'=>$adata2,'label'=>$label,'glabel'=>$glabel,'daydari'=>$fildari,'daysampai'=>$filsampai]);
                }
                if($fil1 == 'HOUR_1'){
                    echo json_encode(['status'=>true,'labelgf'=>$labelgf,'data'=>$adata2,'label'=>$label,'glabel'=>$glabel,'hourdari'=>$filhour]);
                }
            }else{
                echo json_encode(['status'=>false,'message'=>'<p style="font-size: 14px">Data Tidak Ditemukan.</p>']);
            }
        }
    }

    public function data_select(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2');
            $fil3 = $this->input->post('value3');

            $this->db->select('image2.nama_data AS id,kode_data.nama_data AS text');
            $this->db->from('image2');
            $this->db->where(['image2.kategori'=>$fil1,'image2.kode_kandang'=>$fil2,'image2.periode'=>$fil3,'image2.kode_perusahaan'=>$id_user]);
            $this->db->join('kode_data','kode_data.kode_data = image2.nama_data','left');
            $this->db->group_by('image2.nama_data');
            $this->db->order_by('kode_data.urutan','ASC');
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

    public function data_select_kandang(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');

            $this->db->select('id,nama_kandang AS text');
            $this->db->from('data_kandang');
            $this->db->where([
                'kode_perusahaan'=>$id_user,
            ]);
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

    public function datatabel(){
        $fil1 = $this->input->post('value1');
        $fil2 = $this->input->post('value2');
        $fil3 = $this->input->post('value3');
        $id_user   = $this->session->userdata('id_user');
        $fildari   = $this->input->post('value4');
        $filsampai = $this->input->post('value5');
        $filhour = $this->input->post('value6');
        $filperiode = $this->input->post('value7');

        if($fil1 == 'DAY_1'){
            if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
                $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
                $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }
        }
        if($fil1 == 'HOUR_1'){
            if($filhour == '-1'){
                $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }
        }

        header('Content-Type: application/json');
        if ($fil1 == 'DAY_1') {echo $this->grafik_model->json_day($fil1,$fil2,$fil3,$id_user,$fildari,$filsampai,$filperiode);}
        if ($fil1 == 'HOUR_1') {echo $this->grafik_model->json_hour($fil1,$fil2,$fil3,$id_user,$filhour,$filperiode);}
    }
}
