<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');        
        $this->load->library('datatables');
        $this->load->model('grafik_model');
    }

    public function index(){
        echo "Silent Is Gold";
    }

    public function history_house_day($site=null)
    {
        redirect('report/history_house_hour');
    }

    public function history_house_hour($site=null)
    {
        if($site == null){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'Report Histori House',
            'head1'     => 'Report',
            'link1'     => '#',
            'head2'     => 'Histori House',
            'link2'     => 'report/history_house_hour',
            'head3'     => '<b>Multi Data</b>',
            'link3'     => '#',
            'isi'       => 'report/house_hour/list',
            'cssadd'    => 'report/house_hour/cssadd',
            'jsadd'     => 'report/house_hour/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
        }
        if($site == 'myaxis'){
            $this->konfigurasi->cek_url();
            $data = [
                'txthead1'     => 'Report Histori House - Double Yaxis',
                'head1'     => 'Report',
                'link1'     => '#',
                'head2'     => '<b>Histori House</b>',
                'link2'     => 'report/history_house_hour',
                'head3'     => '<b>Yaxis Ganda</b>',
                'link3'     => '#',
                'isi'       => 'report/house_hour/myaxis/list',
                'cssadd'    => 'report/house_hour/myaxis/cssadd',
                'jsadd'     => 'report/house_hour/myaxis/jsadd',
            ];
            $this->load->view('template/wrapper',$data);
        }

    }

    public function datahh($url)
    {
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2')[$url];
            $fil3 = $this->input->post('value3');
            $id_user   = $this->session->userdata('id_user');
            $filhour1 = $this->input->post('value61');
            $filhour2 = $this->input->post('value62');
            $filperiode = $this->input->post('value7');
            $nomor = $this->input->post('valnomor');
            $valpem = $this->input->post('valpem');

            $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
            $stringkd = [];
            foreach ($namakd as $key) {
                $stringkd[$key->id] = $key->nama_kandang;
            }

            if($filhour1 == $filhour2){
                if($filhour1 == '-1'){
                    $filhour1 = $this->db->query("SELECT growday FROM data_record WHERE periode = '".$filperiode."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY growday DESC LIMIT 1")->row_array()['growday'];
                    $filhour2 = $filhour1;
                }
                $esqlgrow = "AND growday = '".$filhour1."'";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$filhour1."' AND '".$filhour2."'";
            }

            $esqlgen  = "SELECT growday, date_record,".$fil2." AS isidata FROM `data_record` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' ";
            $esqlorder = "ORDER BY date_record ASC";

            if($fil1 == 'HOUR_1'){
                if($filhour1 == $filhour2){
                    $addlabel = ' : Grow Day '.$filhour1.' ('.$filperiode.')';
                }else{
                    $addlabel = ' : Grow Day '.$filhour1.' - '.$filhour2.' ('.$filperiode.')';
                }
            }

            $xlabel = $this->grafik_model->list_data('all');

            $glabel = $xlabel[$fil2].$addlabel;
            $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';

            //Data Utama
            $esqlprimary  = $esqlgen;
            $esqlprimary .= "AND kode_kandang = '".$fil3."' ";
            $esqlprimary .= "AND periode = '".$filperiode."' ";
            $esqlprimary .= $esqlgrow;
            $esqlprimary .= $esqlorder;

            $dataprimary = $this->db->query($esqlprimary)->result();

            $adata = [];
            foreach ($dataprimary as $value) {
                $jam = date_format(date_create($value->date_record),"H");
                $adata[] = '('.$value->growday.') - '.$jam.':00';
            }
            $isigrowday = $adata;

            $bdata = [];
            foreach ($dataprimary as $value2) {
                $bdata[] = $value2->isidata;
            }
            $isiprimary = $bdata;

            //Data Pembanding
            $urutan = 7;
            $countsecond = 0;
            $isisecondary = [];
            for ($i=0; $i < $nomor; $i++) { 
                $urutan = $urutan + 1;
                $esqlsecondary  = $esqlgen;
                $esqlsecondary .= "AND kode_kandang = '".$valpem['valkandang'.$urutan]."' ";
                $esqlsecondary .= "AND periode = '".$valpem['valperiode'.$urutan]."' ";
                $esqlsecondary .= $esqlgrow;
                $esqlsecondary .= $esqlorder;
                $datasecondary = $this->db->query($esqlsecondary)->result();
                $cdata = [];
                if ($datasecondary == null) {
                    for ($j=0; $j < count($isigrowday); $j++) { 
                        $cdata[$j] = 0;
                    }
                }else{
                    for ($k=0; $k < count($isigrowday); $k++) { 
                        $cdata[$k] = '';
                        foreach ($datasecondary as $value3) {
                            $jam2 = date_format(date_create($value3->date_record),"H");
                            if($isigrowday[$k] == ('('.$value->growday.') - '.$jam2.':00')){
                                $cdata[$k] = $value3->isidata;
                            }
                        }
                        if($cdata[$k] == '' OR $cdata[$k] == null){
                            $cdata[$k] = 0; 
                        }
                    }
                }
                $isisecondary[] = $cdata;
                $countsecond = $countsecond + 1;
                $linelabel[$i+1] = $stringkd[$valpem['valkandang'.$urutan]].' ('.$valpem['valperiode'.$urutan].')';
            }

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday,'data'=>$isiprimary,'label'=>$xlabel[$fil2],'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'datasecond'=>$isisecondary,'countsecond'=>$countsecond,'linelabel'=>$linelabel]);

    }

    public function dataaxish(){
        $data1kandang = $this->input->post('data1kandang');
        $data1periode = $this->input->post('data1periode');
        $data1data    = $this->input->post('data1data');
        $data2data    = $this->input->post('data2data');
        $filhour1     = $this->input->post('value61');
        $filhour2     = $this->input->post('value62');
        $id_user      = $this->session->userdata('id_user');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

        if($filhour1 == $filhour2){
            if($filhour1 == '-1'){
                $filhour1 = $this->db->query("SELECT growday FROM data_record WHERE periode = '".$data1periode."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ORDER BY growday DESC LIMIT 1")->row_array()['growday'];
                $filhour2 = $filhour1;
            }
            $esqlgrow = "AND growday = '".$filhour1."'";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$filhour1."' AND '".$filhour2."'";
        }

        $esqlgen  = "SELECT growday, date_record,".$data1data." AS isidata,".$data2data." AS isidata2 FROM `data_record` ";
        $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ";
        $esqlorder = "ORDER BY date_record ASC";

        $xlabel = $this->grafik_model->list_data('all');

            $glabel = $this->input->post('namagrafik');
            $linelabel[0] = $xlabel[$data1data];

            //Data Utama
            $esql1  = $esqlgen;
            $esql1 .= "AND periode = '".$data1periode."' ";
            $esql1 .= $esqlgrow;
            $esql1 .= $esqlorder;

            $dataprimary1 = $this->db->query($esql1)->result();

            $adata = [];
            foreach ($dataprimary1 as $value) {
                $jam = date_format(date_create($value->date_record),"H");
                $adata[] = '('.$value->growday.') - '.$jam.':00';
            }
            $isigrowday1 = $adata;

            $bdata = [];
            $cdata2 = [];
            foreach ($dataprimary1 as $value2) {
                $bdata[] = $value2->isidata;
                $cdata2[] = $value2->isidata2;
            }
            $isidatagrafik[0] = $bdata;
            $isidatagrafik[1] = $cdata2;
            $linelabel[1] = $xlabel[$data2data];
            //END Data 2

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'linelabel'=>$linelabel]);
    }

    public function datatabel_dyaxish(){
        $fil2 = $this->input->post('data1data');

        $fil3 = $this->input->post('data1kandang');
        $id_user   = $this->session->userdata('id_user');
        $filhour1      = $this->input->post('value61');
        $filhour2      = $this->input->post('value62');
        $filperiode = $this->input->post('data1periode');

        $data2data    = $this->input->post('data2data');

        if($fil2 == ''){$fil2 = "id";}
        if($data2data == ''){$data2data = "id";}

        header('Content-Type: application/json');
        if($filhour1 == $filhour2){
            echo $this->grafik_model->json_hour2($fil2,$fil3,$id_user,$filhour1,$filperiode,$data2data);
        }else{
            echo $this->grafik_model->json_hour2_between($fil2,$fil3,$id_user,$filhour1,$filhour2,$filperiode,$data2data);
        }
    }

    public function data_select(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $idlabel = $this->grafik_model->list_data('idselect');

            $textlabel = $this->grafik_model->list_data('textselect');

            for ($i=0; $i < count($idlabel); $i++) { 
                $dataini = [
                    'id'   => $idlabel[$i],
                    'text' => $textlabel[$i],
                ];
                $dataini1[] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }    

    public function data_selectdy(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $idlabel = $this->grafik_model->list_data('idselectdy');

            $textlabel = $this->grafik_model->list_data('textselectdy');

            for ($i=0; $i < count($idlabel); $i++) { 
                $dataini = [
                    'id'   => $idlabel[$i],
                    'text' => $textlabel[$i],
                ];
                $dataini1[] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }    
}
