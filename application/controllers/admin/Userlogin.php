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
    			'id_user'   => $this->input->post('nama_farm',TRUE),
    			'userlogin' => $this->input->post('username',TRUE),
    			'username'  => $this->input->post('username',TRUE),
    			'password'  => md5($this->input->post('password',TRUE)),
    			'status_user' => '1'
			);

            $this->umum_model->insert('data_operator',$data);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function update(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $data = array(
    			'id_user'   => $this->input->post('nama_farm',TRUE),
    			'userlogin' => $this->input->post('username',TRUE),
    			'username'  => $this->input->post('username',TRUE),
    			'status_user' => '1'
			);

            if($this->input->post('password',TRUE) != ''){
    			$data['password']  = md5($this->input->post('password',TRUE));
            }
            
            $where = ['id' => $this->input->post('id',TRUE)];

            $this->umum_model->update('data_operator',$data,$where);
            echo json_encode(['status' => true,'message' => 'Data berhasil disimpan!']);
        }
    }

    public function delete(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $this->umum_model->delete('data_operator',['id' => $this->input->post('value')]);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function edit($id){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $row = $this->umum_model->get('data_operator',['id' => $id])->row();
            $row2 = $this->umum_model->get('user',['id' => $row->id_user])->row();

            $data = array(
                'id' => $row->id,
                'id_farm' => $row->id_user,
                'nama_farm' => $row2->nama_user,
    			'username' => $row->username
			);
			
    	    echo json_encode(['status' => true, 'data' => $data]);
        }
    }

    public function select_user(){
        $fil=$this->input->get('search')?$this->input->get('search'):NULL;
        $this->db->select(['id','nama_user']);
        $this->db->from('user');
        $this->db->like('nama_user',$fil,'both');
        $this->db->limit(50);
        $db =$this->db->get();
        $data = [];
        foreach ($db->result() as $d) {
            $data[]= array(
                'id'   => $d->id,
                'text' => $d->nama_user,
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
