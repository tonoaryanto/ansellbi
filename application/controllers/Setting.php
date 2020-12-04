<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    public function index(){
        echo "Silent Is Gold";
    }

    public function standard_value($form=null,$dataform=null){
        $this->konfigurasi->cek_url();
        if($dataform != null and $this->tpvalue($dataform) == ''){redirect(base_url('setting/standard_value/'));}
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
                'tpval' => $this->tpvalue($dataform)
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

    public function load_standard_val(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_farm = $this->session->userdata('id_user');
            $kode_kandang = $this->input->post('nama_kandang');
            $tpval = $this->input->post('tpval');
            $datasave = [
                'kode_farm' => $id_farm,
                'kode_kandang' => $kode_kandang,
            ];
            $inidb = $this->umum_model->get('standar_value',$datasave);
            $cekdb = $inidb->num_rows();
            if($cekdb > 0){
                $isidb = $inidb->row_array();
                $iniweek = explode(',',$isidb[$tpval]);
                $dataini = [
                    'dataweek' => $iniweek,
                    'countweek' => count($iniweek)
                ];
                echo json_encode(['status' => true, 'dataSet' => $dataini]);
            }else{
                echo json_encode(['status' => false]);
            }
        }
    }

    public function save_standard_val(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('nama_kandang');
        $week = $this->input->post('week');
        $tpval = $this->input->post('tpval');

        $hasilweek = $this->iniweek($week);
        
        $datasave = [
            'kode_farm' => $id_farm,
            'kode_kandang' => $kode_kandang,
        ];
        $where = $datasave;
        $datasave[$tpval] = $hasilweek;

        $cekdb = $this->umum_model->get('standar_value',$where)->num_rows();
        if($cekdb > 0){
            $this->umum_model->update('standar_value',$datasave,$where);
        }else{
            $this->umum_model->insert('standar_value',$datasave);
        }

        echo $this->db->affected_rows();
    }

    private function iniweek($week)
    {
        $opweek1 = explode(',',$week[0]);
        $nop1 = $opweek1[0];
        if(count($opweek1) > 1){
            for($a1=1; $a1 < count($opweek1); $a1++){
                $nop1 .= '.'.$opweek1[$a1];
            }
        }

        $hasilweek = $nop1;
        for ($i=1; $i < count($week); $i++) { 
            $opweek = explode(',',$week[$i]);
            $nop = $opweek[0];
            if(count($opweek) > 1){
                for($a=1; $a < count($opweek); $a++){
                    $nop .= '.'.$opweek[$a];
                }
            }
            $hasilweek .= ','.$nop;
        }
        return $hasilweek;
    }

    private function tpvalue($data)
    {
            $ini = [
            'temperature' => 'std_temp',
            'humidity' => 'std_humidity',
            'speed_fan' => 'std_fanspeed',
            'wind_speed' => 'std_wind_speed',
            'body_weight' => 'std_body_weight',
            'egg_weight' => 'std_egg_weight',
            'egg_counter' => 'std_egg_counter',
            'water' => 'std_water',
            'feed' => 'std_feed',
            'static_pressure' =>'std_static_press'
        ];

        return isset ($ini[$data]) ? $ini[$data]:'';
    }
}