<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
		$this->load->library('datatables');
    }

	public function index(){
		$cek = $this->session->userdata('id_user');
		if($cek == ''){
			$this->load->view('login/list');			
		}else{
			redirect('history_house');
		}
	}

	public function masuk(){
		$data = $this->umum_model->get('data_operator',['username' => $this->input->post('username',TRUE),'password' => md5($this->input->post('password',TRUE))]);
		if ($data->num_rows() == 1) {
			$data_akses = $data->row_array();
			$data_pegawai = $this->umum_model->get('user',['id'=>$data_akses['id_user']])->row_array();

			$dat_sess = [
				'id_login' 	=> $data_akses['id'],
				'id_user' 	=> $data_akses['id_user'],
				'nama_user' => $data_pegawai['nama_user'],
				'flock_id' 	=> $data_pegawai['flock_id'],
				'status_user' => $data_akses['status_user']
			];

			if ($data_akses['status_user'] == '1') {
				$url = base_url('history_house');
			}else if($data_akses['status_user'] == '2'){
				$url = base_url('admin/farm');
			}

			$this->session->set_userdata($dat_sess);
			echo json_encode(['status'=>true,'nama_user'=>$data_pegawai['nama_user'],'url'=>$url]);
		}else{
			echo json_encode(['status'=>false,'message'=>'Logon Failure, Unknown <b>username</b> or bad <b>password</b>. Please check again!']);
		}
	}

    public function keluar(){
        $this->session->sess_destroy();
        redirect('login');
    }

    public function keamanan(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
        	$id_login = $this->session->userdata('id_login');
        	$password = md5(base64_decode($this->input->post('value')));
			$data = $this->umum_model->get('data_operator',[
				'id' => $id_login,
				'password' => $password,
			]);
			if ($data->num_rows() == 1) {
				echo json_encode(['status'=>true]);
			}else{
				echo json_encode(['status'=>false]);	
			}
        }
    }
}
