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

    public function getflock(){
        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $esql2 = "SELECT id,flock FROM data_kandang WHERE id = '".$kode_kandang."'";
        $cekdb2 = $this->db->query($esql2);
        $isidb21 = $cekdb2->row_array();

        if($cekdb2->num_rows() > 0){
            $rcekdb3 = $this->db->query("SELECT growday FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$isidb21['id']."' ORDER BY date_record DESC lIMIT 1");
            $cekdb3 = $rcekdb3->row_array();

            if($rcekdb3->num_rows() > 0){
                $cekdb3min = $this->db->query("SELECT date_record FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$isidb21['id']."' AND growday = '".$cekdb3['growday']."' ORDER BY date_record ASC lIMIT 1")->row_array();
                $cekdb3max = $this->db->query("SELECT date_record FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$isidb21['id']."' AND growday = '".$cekdb3['growday']."' ORDER BY date_record DESC lIMIT 1")->row_array();
    
                $tanggalawal = date_format(date_create($cekdb3min['date_record']),"Y-m-d");
                $tanggalakhir = date_format(date_create($cekdb3max['date_record']),"Y-m-d");
                $jam1 = date_format(date_create($cekdb3min['date_record']),"H:i");
                $jam2 = date_format(date_create($cekdb3max['date_record']),"H:i");
                $growday = $cekdb3['growday'];    
            }else{
                $tanggalawal = '';
                $tanggalakhir = '';
                $jam1 = '';
                $jam2 = '';
                $growday = '';
            }
        }else{
            $tanggalawal = '';
            $tanggalakhir = '';
            $jam1 = '';
            $jam2 = '';
            $growday = '';
        }

        if($cekdb2->num_rows() > 0){
            echo json_encode([
                'status'        => true,
                'periode'       => $isidb21['flock'],
                'tanggalawal'   => $tanggalawal,
                'tanggalakhir'  => $tanggalakhir,
                'jam1'          => $jam1,
                'jam2'          => $jam2,
                'growawal'      => $growday,
                'growakhir'     => $growday
                ]);
        }else{
            echo json_encode(['status'=>false]);
        }
    }

    public function changetgl(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $periode = $this->input->post('periode');
        $startgl = $this->input->post('tgl');
        $startime = $this->input->post('time').":00";
        $urut = $this->input->post('dt');

        $diff2 = date_format(date_create($startgl." ".$startime),"Y-m-d H:i:s");
        $where = "periode = '".$periode."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok'";
        if($urut == 1){
            $house = $this->db->query("SELECT growday,date_record,reset_time FROM data_record WHERE date_record >= '".$diff2."' AND ".$where." ORDER BY date_record ASC LIMIT 1")->row_array();
        }else if($urut == 2){
            $house = $this->db->query("SELECT growday,date_record,reset_time FROM data_record WHERE date_record <= '".$diff2."' AND ".$where."  ORDER BY date_record DESC LIMIT 1")->row_array();
        }
        $date_in = date_create(date_format(date_create($house['date_record']),"Y-m-d")." ".date_format(date_create($house['reset_time']),"H:i:s"));

        $difftgl1 = date_diff($date_in,date_create($diff2));
        $growset = (int)$house['growday'] + (int)$difftgl1->format("%R%a");
        $timeset = date_format(date_create($house['date_record']),"H:i");

        if($house['growday'] != ''){
            echo json_encode(['status' => true, 'dataset' => $growset,'timeset' => $timeset]);
        }else{
            echo json_encode(['status' => false]);
        }
    }

    public function changegrow(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {echo json_encode(['sess' => $cek_sess]);return;}

        $id_farm = $this->session->userdata('id_user');
        $kode_kandang = $this->input->post('kandang');
        $periode = $this->input->post('periode');
        $grow1 = $this->input->post('grow');
        $urut = $this->input->post('dt');

        $house2 = $this->db->query("SELECT growday FROM data_record WHERE periode = '".$periode."' AND growday <= '".$grow1."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok' ORDER BY date_record DESC LIMIT 1")->row_array();

        $where = "periode = '".$periode."' AND growday = '".$house2['growday']."' AND kode_perusahaan = '".$id_farm."' AND kode_kandang = '".$kode_kandang."' AND keterangan = 'ok'";
        if($urut == 1){
            $house = $this->db->query("SELECT growday,date_record FROM data_record WHERE ".$where." ORDER BY date_record ASC LIMIT 1")->row_array();
        }else if($urut == 2){
            $house = $this->db->query("SELECT growday,date_record FROM data_record WHERE ".$where."  ORDER BY date_record DESC LIMIT 1")->row_array();
        }

        $diffgrow = (int)$grow1 - (int)$house['growday'];

        $date=date_create($house['date_record']);
        date_modify($date,$diffgrow." days");
        $tglset = date_format($date,"Y-m-d");
        $timeset = date_format(date_create($house['date_record']),"H:i");

        if($house['date_record'] != ''){
            echo json_encode(['status' => true, 'dataset' => $tglset,'timeset'=>$timeset]);
        }else{
            echo json_encode(['status' => false]);
        }
    }


    public function history_house_hour($site=null)
    {
        $id_user   = $this->session->userdata('id_user');
        $hgrowchange = $this->umum_model->get("(SELECT periode,growday FROM data_record WHERE keterangan = 'growchange' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC,growday DESC LIMIT 1) as data")->num_rows();

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
            'cekgrowchange' => $hgrowchange
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
                'cekgrowchange' => $hgrowchange
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
                    $filhour1 = $this->db->query("SELECT growday FROM data_record WHERE keterangan = 'ok' AND periode = '".$filperiode."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY growday DESC LIMIT 1")->row_array()['growday'];
                    $filhour2 = $filhour1;
                }
                $esqlgrow = "AND growday = '".$filhour1."'";
            }else{
                $esqlgrow = "AND growday BETWEEN '".$filhour1."' AND '".$filhour2."'";
            }

            $esqlgen  = "SELECT growday, date_record,".$fil2." AS isidata FROM `data_record` ";
            $esqlgen .= "WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' ";
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
                $adata[] = ''.$value->growday.' - '.$jam;
            }
            $isigrowday = $adata;

            $bdata = [];
            foreach ($dataprimary as $value2) {
                $bdata[] = $value2->isidata;
            }
            $isiprimary = $bdata;

            $esqlmin1  = "SELECT MAX($fil2) as maxdata1,MIN($fil2) as mindata1 FROM `data_record` WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ";
            $esqlmin1 .= "AND periode = '".$filperiode."' ";
            $esqlmin1 .= $esqlgrow;
            $esqlmin1 .= $esqlorder;

            $datarange = $this->db->query($esqlmin1)->row_array();

            if((int)$datarange['maxdata1'] == 100){$datamax1[0] = (int)$datarange['maxdata1'];}else{$datamax1[0] = (int)$datarange['maxdata1'] + 1;}
            if((int)$datarange['mindata1'] == 0){$datamin1[0] = (int)$datarange['mindata1'];}else{$datamin1[0] = (int)$datarange['mindata1'] - 1;}

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
                            if($isigrowday[$k] == (''.$value->growday.' - '.$jam2)){
                                $cdata[$k] = $value3->isidata;
                            }
                        }
                        if($cdata[$k] == '' OR $cdata[$k] == null){
                            $cdata[$k] = 0;
                        }
                    }
                    $esqlmin1c  = "SELECT MAX($fil2) as maxdata1,MIN($fil2) as mindata1 FROM `data_record` WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$valpem['valkandang'.$urutan]."' ";
                    $esqlmin1c .= "AND periode = '".$valpem['valperiode'.$urutan]."' ";
                    $esqlmin1c .= $esqlgrow;
                    $esqlmin1c .= $esqlorder;
        
                    $datarangec = $this->db->query($esqlmin1)->row_array();

                    if((int)$datarangec['maxdata1'] == 100){$datamax1[$i + 1] = (int)$datarange['maxdata1'];}else{$datamax1[$i + 1] = (int)$datarange['maxdata1'] + 1;}
                    if((int)$datarangec['mindata1'] == 0){$datamin1[$i + 1] = (int)$datarange['mindata1'];}else{$datamin1[$i + 1] = (int)$datarange['mindata1'] - 1;}
                }
                $isisecondary[] = $cdata;
                $countsecond = $countsecond + 1;
                $linelabel[$i+1] = $stringkd[$valpem['valkandang'.$urutan]].' ('.$valpem['valperiode'.$urutan].')';
            }
            $difgrow = $filhour1 - $filhour2;

            $realmax = max($datamax1);
            $realmin = min($datamin1);

            $countrange = 10;
            $dif1 = $realmax - $realmin;
            if($dif1 == $realmax){$dif1range = $dif1 / 10;}
            else{$dif1range = $dif1 / $countrange;}
            $sizeyaxis1[0] = $realmin;
            if($dif1range < 1){$dif1range = 1;}
            if(isset(explode(".",$dif1range)[1])){
                if(explode(".",$dif1range)[1] >= 1){$dif1range = explode(".",$dif1range)[0] + 1;}
            };
            for ($i=0; $i < $countrange; $i++) { 
                $realmin = $realmin + $dif1range;
                $sizeyaxis1[$i+1] = $realmin;
            }

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday,'data'=>$isiprimary,'label'=>$xlabel[$fil2],'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'datasecond'=>$isisecondary,'countsecond'=>$countsecond,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1'=>$sizeyaxis1]);
    }

    public function dataaxish(){
        $data1kandang = $this->input->post('data1kandang');
        $data1periode = $this->input->post('data1periode');
        $data1data    = $this->input->post('data1data');
        $data2data    = $this->input->post('data2data');
        $tgl1     = $this->input->post('tgl1');
        $tgl2     = $this->input->post('tgl2');
        $time1     = $this->input->post('time1');
        $time2     = $this->input->post('time2');
        $filhour1     = $this->input->post('value61');
        $filhour2     = $this->input->post('value62');
        $id_user      = $this->session->userdata('id_user');

        $vtgl1 = date_format(date_create($tgl1),"Y-m-d");
        $vtgl2 = date_format(date_create($tgl2),"Y-m-d");
        $vtime1 = date_format(date_create($time1.":00"),"H:i:s");
        $vtime2 = date_format(date_create($time2.":00"),"H:i:s");
        $tglaw = date_format(date_create($vtgl1." ".$vtime1),"Y-m-d H:i:s");
        $tglen = date_format(date_create($vtgl2." ".$vtime2),"Y-m-d H:i:s");

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

        if($filhour1 == $filhour2){
            $esqlgrow = "AND growday = '".$filhour1."'";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$filhour1."' AND '".$filhour2."'";
        }

        $esqlgen  = "SELECT growday, date_record,".$data1data." AS isidata,".$data2data." AS isidata2 FROM `data_record` ";
        $esqlgen .= "WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ";
        $esqlorder = "ORDER BY date_record ASC";

        $xlabel = $this->grafik_model->list_data('all');

            $glabel = $this->input->post('namagrafik');
            $linelabel[0] = $xlabel[$data1data];

            //Data Utama
            $esql1  = $esqlgen;
            $esql1 .= "AND periode = '".$data1periode."' ";
            $esql1 .= $esqlgrow;
            $esql1 .= "AND date_record >= '".$tglaw."' AND date_record <= '".$tglen."'";
            $esql1 .= $esqlorder;

            $dataprimary1 = $this->db->query($esql1)->result();

            $adata = [];
            foreach ($dataprimary1 as $value) {
                $jam = date_format(date_create($value->date_record),"H");
                $adata[] = ''.$value->growday.' - '.$jam;
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
            $difgrow = $filhour1 - $filhour2;

            $esqlmin1  = "SELECT MAX($data1data) as maxdata1,MAX($data2data) as maxdata2, MIN($data1data) as mindata1,MIN($data2data) as mindata2 FROM `data_record` WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ";
            $esqlmin1 .= "AND periode = '".$data1periode."' ";
            $esqlmin1 .= $esqlgrow;
            $esqlmin1 .= "AND date_record >= '".$tglaw."' AND date_record <= '".$tglen."'";
            $esqlmin1 .= $esqlorder;

            $datarange = $this->db->query($esqlmin1)->row_array();

            $datamax1 = $datarange['maxdata1'];
            $datamax2 = $datarange['maxdata2'];
            $datamin1 = $datarange['mindata1'];
            $datamin2 = $datarange['mindata2'];
        
            $countrange = 8;
            $dif1 = $datamax1 - $datamin1;
            $dif1range = $dif1 / $countrange;
            if($dif1range < 1){$dif1range = 1;}
            if(isset(explode(".",$dif1range)[1])){
                if(explode(".",$dif1range)[1] >= 1){
                    $dif1range = explode(".",$dif1range)[0] + 1;
                }else{
                    $dif1range = explode(".",$dif1range)[0];
                }
            }

            if(isset(explode(".",$datamin1)[1])){
                if(explode(".",$datamin1)[0] >= 1){
                    $datamin1 = explode(".",$datamin1)[0] - 1;
                }else{
                    $datamin1 = explode(".",$datamin1)[0];
                }
            }
    
            if(isset(explode(".",$datamax1)[1])){
                if(explode(".",$datamax1)[1] >= 1){
                    $datamax1 = explode(".",$datamax1)[0] + 1;
                }else{
                    $datamax1 = explode(".",$datamax1)[0];
                }
            }

            $dif2 = $datamax2 - $datamin2;
            $dif2range = $dif2 / $countrange;
            if($dif2range < 1){$dif2range = 1;}
            if(isset(explode(".",$dif2range)[1])){
                if(explode(".",$dif2range)[1] >= 1){
                    $dif2range = explode(".",$dif2range)[0] + 1;
                }else{
                    $dif2range = explode(".",$dif2range)[0];
                }
            }
    
            if(isset(explode(".",$datamin2)[1])){
                if(explode(".",$datamin2)[0] >= 1){
                    $datamin2 = explode(".",$datamin2)[0] - 1;
                }else{
                    $datamin2 = explode(".",$datamin2)[0];
                }
            }
    
            if(isset(explode(".",$datamax2)[1])){
                if(explode(".",$datamax2)[1] >= 1){
                    $datamax2 = explode(".",$datamax2)[0] + 1;
                }else{
                    $datamax2 = explode(".",$datamax2)[0];
                }
            }
            
            $sizeyaxis1[0] = floatval(number_format($datamin1,2));
            if(($datamax1 - $datamin1) <= 5){$dif1range = $dif1range / 2;}
            if(($datamax1 - $datamin1) <= 2){$dif1range = ($dif1range * 2) / 5;}

            $sizeyaxis2[0] = floatval(number_format($datamin2,2));
            if(($datamax2 - $datamin2) <= 5){$dif2range = $dif2range / 2;}
            if(($datamax2 - $datamin2) <= 2){$dif2range = ($dif2range * 2) / 5;}

            for ($i=0; $i < $countrange; $i++) { 
                $datamin1 = $datamin1 + $dif1range;
                $datamin2 = $datamin2 + $dif2range;
                if($datamin1 <= 200){
                    $sizeyaxis1[$i+1] = floatval(number_format($datamin1,2));
                }else{
                    $sizeyaxis1[$i+1] = (int)number_format($datamin1,0,",","");
                }

                if($datamax2 <= 200){
                    $sizeyaxis2[$i+1] = floatval(number_format($datamin2,2));
                }else{
                    $sizeyaxis2[$i+1] = (int)number_format($datamin2,0,",","");
                }
                if($sizeyaxis1[$i+1] >= $datamax1 AND $sizeyaxis2[$i+1] >= $datamax2){break;}else{
                    if(($i + 1) == $countrange){
                        $countrange = $countrange + 1;
                    }
                }
            }

            echo json_encode(['status'=>true,'labelgf'=>$isigrowday1,'data'=>$isidatagrafik,'glabel'=>$glabel,'hourdari1'=>$filhour1,'hourdari2'=>$filhour2,'linelabel'=>$linelabel,'difgrow'=>$difgrow,'sizeyaxis1'=>$sizeyaxis1,'sizeyaxis2'=>$sizeyaxis2]);
    }

    public function datatabel_dyaxish(){
        $fil2 = $this->input->post('data1data');

        $fil3 = $this->input->post('data1kandang');
        $id_user   = $this->session->userdata('id_user');
        $growval      = $this->input->post('value61');
        $growval2      = $this->input->post('value62');
        $filperiode = $this->input->post('data1periode');
        $data2data    = $this->input->post('data2data');
        $tgl1     = $this->input->post('tgl1');
        $tgl2     = $this->input->post('tgl2');
        $time1     = $this->input->post('time1');
        $time2     = $this->input->post('time2');

        $vtgl1 = date_format(date_create($tgl1),"Y-m-d");
        $vtgl2 = date_format(date_create($tgl2),"Y-m-d");
        $vtime1 = date_format(date_create($time1.":00"),"H:i:s");
        $vtime2 = date_format(date_create($time2.":00"),"H:i:s");
        $tglaw = date_format(date_create($vtgl1." ".$vtime1),"Y-m-d H:i:s");
        $tglen = date_format(date_create($vtgl2." ".$vtime2),"Y-m-d H:i:s");

        if($fil2 == ''){$fil2 = "id";}
        if($data2data == ''){$data2data = "id";}

        if($growval == $growval2){
            $esqlgrow = "AND growday = '".$growval."' ";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$growval."' AND '".$growval2."' ";
        }
        
        $datsql1  = "SELECT id,growday, date_record,";
        $datsql1 .= $fil2.",".$data2data;
        $datsql1 .= " FROM data_record WHERE keterangan = 'ok' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ";
        $datsql1 .= "AND periode = '".$filperiode."' ";
        $datsql1 .= $esqlgrow;
        $datsql1 .= "AND date_record >= '".$tglaw."' AND date_record <= '".$tglen."'";
        $datsql1 .= "ORDER BY date_record ASC";

        //Data Utama
        $dataprimary1 = $this->db->query($datsql1);

        $adata = [];
        for ($iz=0; $iz < $dataprimary1->num_rows(); $iz++) {
            $isidata = $dataprimary1->row_array($iz);
            $kolomdata = [];
            $kolomdata[0]  = $iz + 1;
            $kolomdata[1]  = $isidata['growday'];
            $kolomdata[2]  = date_format(date_create($isidata['date_record']),"d-m-Y");
            $kolomdata[3]  = date_format(date_create($isidata['date_record']),"H:i:s");
            $kolomdata[4]  = $isidata[$fil2];
            $kolomdata[5]  = $isidata[$data2data];
            $adata[$iz] = $kolomdata;
        }
        //END Data Utama

        header('Content-Type: application/json');
        echo json_encode(['status' => true, 'dataSet' => $adata]);
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

            $dataini1[0] = ['id' => 'null','text' => '-Select Data-'];
            for ($i=0; $i < count($idlabel); $i++) { 
                $n = $i + 1;
                $dataini = [
                    'id'   => $idlabel[$i],
                    'text' => $textlabel[$i],
                ];
                $dataini1[$n] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }
}
