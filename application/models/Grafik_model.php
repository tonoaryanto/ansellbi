<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grafik_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function json_hour2($val2,$val3,$val4,$val5,$val7,$val27){
        $this->datatables->select("id, DATE_FORMAT(date_record,'%d-%m-%Y') AS ttanggal_value, DATE_FORMAT(date_record,'%H-%i-%s') AS jjam_value, ".$val2." as isi_value1, ".$val27." as isi_value3, growday AS grow_value");
        $this->datatables->from("data_record");
        $this->datatables->where([
            'kode_perusahaan' => $val4,
            'kode_kandang' => $val3,
            'periode' => $val7,
            'growday' => $val5
        ]);
        $data = ['0','2','3','1','2','3','1','2','3'];
        $this->datatables->add_column('isi_value2', '$1',$data[1]);
        return $this->datatables->generate();
    }

    function json_hour2_between($val2,$val3,$val4,$val51,$val52,$val7,$val27){
        $this->datatables->select("id, DATE_FORMAT(date_record,'%d-%m-%Y') AS ttanggal_value, DATE_FORMAT(date_record,'%H-%i-%s') AS jjam_value, ".$val2." as isi_value1, ".$val27." as isi_value3, growday AS grow_value");
        $this->datatables->from("data_record");
        $this->datatables->where([
            'kode_perusahaan' => $val4,
            'kode_kandang' => $val3,
            'periode' => $val7,
        ]);
        $this->datatables->where("growday BETWEEN '".$val51."' AND '".$val52."'");
        $data = ['0','2','3','1','2','3','1','2','3'];
        $this->datatables->add_column('isi_value2', '$1',$data[1]);
        return $this->datatables->generate();
    }

    function grafik_temperature($reqdata)
    {
        $id_user = $reqdata['id_user'];
        $inidata = $reqdata['inidata'];
        $id_farm = $reqdata['id_farm'];
        $esqlperiode = $reqdata['esqlperiode'];
        $esqlgrow = $reqdata['esqlgrow'];
        $growval = $reqdata['growval'];
        $growval2 = $reqdata['growval2'];

        $esql  = "SELECT id,growday, date_record,";
        $esql .= "req_temp AS isidata2,".$inidata." AS isidata";
        $esql .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY date_record ASC";

        $label = [
            'req_temp' => 'Required Temperature',
            'avg_temp' => 'Average Temperature',
            'temp_1' => 'Temperature 1',
            'temp_2' => 'Temperature 2',
            'temp_3' => 'Temperature 3',
            'temp_4' => 'Temperature 4',
            'temp_out' => 'Out Temperature'
        ];

        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = $label[$inidata].$addlabel;
        $linelabel[0] = $label[$inidata];

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

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
        $linelabel[1] = $label['req_temp'];

        $hasildata['isigrowday1'] = $isigrowday1;
        $hasildata['isidatagrafik'] = $isidatagrafik;
        $hasildata['glabel'] = $glabel;
        $hasildata['growval'] = $growval;
        $hasildata['linelabel'] = $linelabel;

        return $hasildata;
    }

    function grafik_satudata($reqdata)
    {
        $id_user = $reqdata['id_user'];
        $inidata = $reqdata['inidata'];
        $id_farm = $reqdata['id_farm'];
        $esqlperiode = $reqdata['esqlperiode'];
        $esqlgrow = $reqdata['esqlgrow'];
        $growval = $reqdata['growval'];
        $growval2 = $reqdata['growval2'];

        $esql  = "SELECT id,growday, date_record,";
        $esql .= $inidata." AS isidata";
        $esql .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY date_record ASC";        

        $label = [
            'humidity' => 'Humidity',
            'feed' => 'Feed Consumtion Kg',
            'water' => 'Water Consumtion Liter',
            'static_pressure' => 'Static Pressure',
            'fan' => 'Fan Speed'
        ];

        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = $label[$inidata].$addlabel;
        $linelabel[0] = $label[$inidata];

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        $adata = [];
        foreach ($dataprimary1 as $value) {
            $jam = date_format(date_create($value->date_record),"H");
            $adata[] = '('.$value->growday.') - '.$jam.':00';
        }
        $isigrowday1 = $adata;

        $bdata = [];
        foreach ($dataprimary1 as $value2) {
            $bdata[] = $value2->isidata;
        }
        $isidatagrafik[0] = $bdata;
        //END Data Utama

        $hasildata['isigrowday1'] = $isigrowday1;
        $hasildata['isidatagrafik'] = $isidatagrafik;
        $hasildata['glabel'] = $glabel;
        $hasildata['growval'] = $growval;
        $hasildata['linelabel'] = $linelabel;

        return $hasildata;
    }

    function grafik_windspeed($reqdata)
    {
        $id_user = $reqdata['id_user'];
        $inidata = $reqdata['inidata'];
        $id_farm = $reqdata['id_farm'];
        $esqlperiode = $reqdata['esqlperiode'];
        $esqlgrow = $reqdata['esqlgrow'];
        $growval = $reqdata['growval'];
        $growval2 = $reqdata['growval2'];

        $esql  = "SELECT id,growday, date_record,";
        $esql .= $inidata[0][0]." AS isidata, ".$inidata[0][1]." AS isidata2, ".$inidata[0][2]." AS isidata3";
        $esql .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
        $esql .= $esqlperiode;
        $esql .= $esqlgrow;
        $esql .= "ORDER BY date_record ASC";

        $label = [
            'windspeed' => 'Wind Speed',
            'min_windspeed' => 'Minimum Wind Speed',
            'max_windspeed' => 'Maximum Wind Speed'
        ];

        if($growval == $growval2){
            $addlabel = ' : Grow Day '.$growval.' ';
        }else{
            $addlabel = ' : Grow Day '.$growval.' - '.$growval2;
        }
        $glabel = 'Wind Speed'.$addlabel;
        $linelabel[0] = $label[$inidata[0][0]];

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

        $adata = [];
        foreach ($dataprimary1 as $value) {
            $jam = date_format(date_create($value->date_record),"H");
            $adata[] = '('.$value->growday.') - '.$jam.':00';
        }
        $isigrowday1 = $adata;

        $bdata = [];
        $cdata2 = [];
        $cdata3 = [];
        foreach ($dataprimary1 as $value2) {
            $bdata[] = $value2->isidata;
            $cdata2[] = $value2->isidata2;
            $cdata3[] = $value2->isidata3;
        }
        $isidatagrafik[0] = $bdata;
        $isidatagrafik[1] = $cdata2;
        $isidatagrafik[2] = $cdata3;
        $linelabel[1] = $label[$inidata[0][1]];
        $linelabel[2] = $label[$inidata[0][2]];

        $hasildata['isigrowday1'] = $isigrowday1;
        $hasildata['isidatagrafik'] = $isidatagrafik;
        $hasildata['glabel'] = $glabel;
        $hasildata['growval'] = $growval;
        $hasildata['linelabel'] = $linelabel;

        return $hasildata;
    }
}