<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Openfarm extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('house_model');
        $this->load->library('datatables');
    }

    public function index(){
        redirect('admin/farm');
    }

    public function data($data){
        $isidata = $this->umum_model->get('user',['id' => $data])->row_array();
        if($isidata['nama_user'] == ''){
            $this->session->unset_userdata('data_openfarm');
            return redirect('admin/farm');
        }
        $this->session->set_userdata([ 'data_openfarm' => $data]);

        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'  => 'Open farm - ' . $isidata['nama_user'],
            'head1'     => 'Data Farm',
            'link1'     => 'admin/farm',
            'head2'     => 'Open Farm - ' . $isidata['nama_user'],
            'link2'     => '#',
            'isi'       => 'admin/open_farm/list',
            'cssadd'    => 'admin/open_farm/cssadd',
            'jsadd'     => 'admin/open_farm/jsadd',
        ];
        $this->load->view('admin/template/wrapper',$data);
    }

    public function json() {
        header('Content-Type: application/json');
        $inidata = $this->session->userdata('data_openfarm');
        echo $this->house_model->json($inidata);
    }

    public function simpan(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $data = array(
    			'nama_kandang' => $this->input->post('nama_house',TRUE),
    			'kode_perusahaan' => $this->session->userdata('data_openfarm')
			);
			
            $this->umum_model->insert('data_kandang',$data);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function delete(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $this->umum_model->delete('image2',['kode_kandang' => $this->input->post('value')]);
            $this->umum_model->delete('history_alarm',['kode_kandang' => $this->input->post('value')]);
            $this->umum_model->delete('data_kandang',['id' => $this->input->post('value')]);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function update(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $data = array(
    			'nama_kandang' => $this->input->post('nama_house',TRUE)
			);
 
            $where = ['id' => $this->input->post('id',TRUE)];

            $this->umum_model->update('data_kandang',$data,$where);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function edit($id){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $row = $this->umum_model->get('data_kandang',['id' => $id])->row();

            $data = array(
    			'id' => $row->id,
    			'nama_house' => $row->nama_kandang,
			);
			
    	    echo json_encode(['status' => true, 'data' => $data]);
        }
    }

}