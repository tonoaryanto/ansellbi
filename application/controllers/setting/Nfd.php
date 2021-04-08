<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Nfd extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
    }

    public function index(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}
        $id_farm  = $this->session->userdata('id_user');

        $dt_kandang = $this->db->query("SELECT id,nama_kandang,kode_perusahaan FROM data_kandang WHERE kode_perusahaan = '".$id_farm."'")->result();
        $countnf = 0;
        $html = "";
        $suhuatas = 1;
        $suhubawah = -2;

        foreach ($dt_kandang as $value) {
            $where = [
                "kode_kandang" => $value->id,
                "kode_perusahaan" => $value->kode_perusahaan,
            ];
            $dbrealtime = $this->umum_model->get("data_realtime",$where);
            $dbrealtimer = $dbrealtime->row_array();
            $suhunow = floatval($dbrealtimer['avg_temp']);
            $suhureq = floatval($dbrealtimer['req_temp']);
            $difsuhu = $suhunow - $suhureq;

            $img = base_url()."assets/icon_software_ansell/data1.png";
            $nama_kandang = $value->nama_kandang;

            if($difsuhu > $suhuatas){
                $countnf = $countnf + 1;
                $statussuhu = "higher";
                $iconarrow = 'fa-arrow-up';
                $colorarrow = '#ce3232';
            }else if($difsuhu < $suhubawah){
                $countnf = $countnf + 1;
                $statussuhu = "lower";
                $iconarrow = 'fa-arrow-down';
                $colorarrow = '#258fff';
            }

            if($difsuhu < $suhubawah OR $difsuhu > $suhuatas){
                $html .= '<li><label><img src="';
                $html .= $img;
                $html .= '" style="height:inherit;width:25px;"><span style="font-weight:lighter;">Temperature</span> (<span>';
                $html .= $nama_kandang;
                $html .= '</span>)</label>';
                $html .= '<p style="padding:0px 10px; 0px"><i class="fa ';
                $html .= $iconarrow;
                $html .= '" style="color:';
                $html .= $colorarrow;
                $html .= ';"></i>&nbsp;Current data is ';
                $html .= $suhunow;
                $html .= "&nbsp;";
                $html .= $statussuhu;
                $html .= '&nbsp;than&nbsp;';
                $html .= $suhureq;
                $html .= '<br><br>';
                $html .= '<span style="float:right;">';
                $html .= $dbrealtimer['date_create'];
                $html .= '</span></p><hr></li>';    
            }
        }

        if($countnf != 0){
            echo json_encode(['status'=>true,'nfd'=>$html,'nfc'=>$countnf]);
        }else{
            echo json_encode(['status'=>false]);
        }
     }
}