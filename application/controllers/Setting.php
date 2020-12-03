<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->model('grafik_model');
    }

    public function index(){
        echo "Silent Is Gold";
    }

    public function standard_value($form=null,$dataform=null){
        $this->konfigurasi->cek_url();
        $dtitle = explode('_',$dataform);
        $datatitle = ucfirst($dtitle[0]);
        if(isset($dtitle[1])){$datatitle .= ' '.ucfirst($dtitle[1]);}
        if($form == 'set'){
            $data = [
                'txthead1'  => 'Input Standard Value',
                'head1'     => 'Setting',
                'link1'     => '#',
                'head2'     => 'Standard Value',
                'link2'     => '#',
                'head3'     => 'Form',
                'link3'     => '#',
                'isi'       => 'setting/form',
                'cssadd'    => 'setting/cssadd',
                'jsadd'     => 'setting/jsadd',
                'texttitle' => $datatitle,
            ];
            $this->load->view('template/wrapper',$data);
        }else{
            $data = [
                'txthead1'  => 'Standard Value',
                'head1'     => 'Setting',
                'link1'     => '#',
                'head2'     => 'Standard Value',
                'link2'     => '#',
                'isi'       => 'setting/list',
                'cssadd'    => 'setting/cssadd',
                'jsadd'     => 'setting/jsadd',
            ];
            $this->load->view('template/wrapper',$data);
        }
    }

    public function add_value(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Standard Value',
            'head1'     => 'Setting',
            'link1'     => '#',
            'head2'     => 'Standard Value',
            'link2'     => '#',
            'isi'       => 'setting/form',
            'cssadd'    => 'setting/cssadd',
            'jsadd'     => 'setting/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

}