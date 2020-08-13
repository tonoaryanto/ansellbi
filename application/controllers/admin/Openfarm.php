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
        $this->session->set_userdata([ 'data_openfarm' => $data]);
        $isifarm = $this->umum_model->get('data_kandang',['kode_perusahaan' => $data])->row_array();
        $isidata = $this->umum_model->get('user',['id' => $data])->row_array();

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
}