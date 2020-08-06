<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct(){
        parent::__construct();
    }

	public function index(){
//        $this->load->view('home_page/list');
        $data_akses = $this->session->userdata('status_user');
        if ($data_akses == '1') {
            redirect('history_house');
        }else if($data_akses == '2'){
            redirect('admin/farm');
        }else{
            redirect('login/keluar');
        }		
	}

}
