<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Userlogin extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'  => 'User Login',
            'head1'     => 'User Login',
            'link1'     => '#',
            'isi'       => 'admin/userlogin/list',
            'cssadd'    => 'admin/userlogin/cssadd',
            'jsadd'     => 'admin/userlogin/jsadd',
        ];
        $this->load->view('admin/template/wrapper',$data);
    }

    public function json() {
        header('Content-Type: application/json');
        echo $this->user_model->json();
    }

    public function simpan(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $data = array(
    			'nama_user' => $this->input->post('nama_farm',TRUE),
    			'alamat_user' => $this->input->post('alamat_farm',TRUE),
    			'keterangan' => 'customer'
			);
			
            $this->umum_model->insert('user',$data);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function update(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $data = array(
    			'nama_user' => $this->input->post('nama_farm',TRUE),
    			'alamat_user' => $this->input->post('alamat_farm',TRUE),
			);
 
            $where = ['id' => $this->input->post('id',TRUE)];

            $this->umum_model->update('user',$data,$where);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function delete(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $ket = $this->umum_model->get('user',['id' => $this->input->post('value')])->row_array()['keterangan'];

            if($ket == 'customer'){
                $this->umum_model->delete('user',['id' => $this->input->post('value')]);                
                $this->umum_model->delete('data_operator',['id_user' => $this->input->post('value')]);
            }
            $this->umum_model->delete('periode',['kode_perusahaan' => $this->input->post('value')]);
            $this->umum_model->delete('image2',['kode_perusahaan' => $this->input->post('value')]);
            $this->umum_model->delete('history_alarm',['id_user' => $this->input->post('value')]);
            $this->umum_model->delete('data_kandang',['kode_perusahaan' => $this->input->post('value')]);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function edit($id){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $row = $this->umum_model->get('user',['id' => $id])->row();

            $data = array(
    			'id' => $row->id,
    			'nama_farm' => $row->nama_user,
    			'alamat_farm' => $row->alamat_user,
			);
			
    	    echo json_encode(['status' => true, 'data' => $data]);
        }
    }

}
