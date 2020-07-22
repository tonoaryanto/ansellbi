<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('grafik_model');
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'  => 'Dashboard',
            'head1'     => 'Dashboard',
            'link1'     => '#',
            'isi'       => 'dashboard/list',
            'cssadd'    => 'dashboard/cssadd',
            'jsadd'     => 'dashboard/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

}
