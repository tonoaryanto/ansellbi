<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grafik_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function size_yaxis($cek)
    {
        //[minimum,maximum,kenaikan]
        $xlabel = [
            'avg_temp' => [21,35,1],
            'temp_1' => [21,35,1],
            'temp_2' => [21,35,1],
            'temp_3' => [21,35,1],
            'temp_4' => [21,35,1],
            'temp_out' => [21,35,1],
            'humidity' => [40,90,10],
            'feed' => [0,100,10],
            'water' => [0,100,10],
            'static_pressure' => [0,100,10],
            'fan' => [0,100,10],
            'windspeed' => [0,4,1],
            'min_windspeed' => [0,4,1],
            'max_windspeed' => [0,4,1]
        ];

        return $xlabel[$cek];
    }

    function list_data($cek)
    {
        $idxlabel = [
            '0' => 'avg_temp',
            '1' => 'temp_1',
            '2' => 'temp_2',
            '3' => 'temp_3',
            '4' => 'temp_4',
            '5' => 'temp_out',
            '6' => 'humidity',
            '7' => 'feed',
            '8' => 'water',
            '9' => 'static_pressure',
            '10' => 'fan',
            '11' => 'windspeed',
            '12' => 'min_windspeed',
            '13' => 'max_windspeed'
        ];
        
        $textxlabel = [
            '0' => 'Average Temperature',
            '1' => 'Temperature 1',
            '2' => 'Temperature 2',
            '3' => 'Temperature 3',
            '4' => 'Temperature 4',
            '5' => 'Out Temperature',
            '6' => 'Humidity',
            '7' => 'Feed Consumtion Kg',
            '8' => 'Water Consumtion Liter',
            '9' => 'Static Pressure',
            '10' => 'Fan Speed',
            '11' => 'Wind Speed',
            '12' => 'Min Wind Speed',
            '13' => 'Max Wind Speed'    
        ];

        switch ($cek) {
            case "idselect":
                $xlabel = [
                    $idxlabel[0],
                    $idxlabel[1],
                    $idxlabel[2],
                    $idxlabel[3],
                    $idxlabel[4],
                    $idxlabel[5],
                    $idxlabel[6],
                    $idxlabel[7],
                    $idxlabel[8],
                    $idxlabel[9],
                    $idxlabel[10],
                    $idxlabel[11],
                    $idxlabel[12],
                    $idxlabel[13]
                ];      
                break;
            case "textselect":
                $xlabel = [
                    $textxlabel[0],
                    $textxlabel[1],
                    $textxlabel[2],
                    $textxlabel[3],
                    $textxlabel[4],
                    $textxlabel[5],
                    $textxlabel[6],
                    $textxlabel[7],
                    $textxlabel[8],
                    $textxlabel[9],
                    $textxlabel[10],
                    $textxlabel[11],
                    $textxlabel[12],
                    $textxlabel[13]    
                ];
                break;
                case "idselectdy":
                    $xlabel = [
                        $idxlabel[0],
                        $idxlabel[5],
                        $idxlabel[6],
                        $idxlabel[7],
                        $idxlabel[8],
                        $idxlabel[9],
                        $idxlabel[10],
                        $idxlabel[11],
                        $idxlabel[12],
                        $idxlabel[13]
                    ];
                    break;
                case "textselectdy":
                    $xlabel = [
                        $textxlabel[0],
                        $textxlabel[5],
                        $textxlabel[6],
                        $textxlabel[7],
                        $textxlabel[8],
                        $textxlabel[9],
                        $textxlabel[10],
                        $textxlabel[11],
                        $textxlabel[12],
                        $textxlabel[13]
                    ];      
                    break;
                case "all":
                    $xlabel[$idxlabel[0]]  = $textxlabel[0];
                    $xlabel[$idxlabel[1]]  = $textxlabel[1];
                    $xlabel[$idxlabel[2]]  = $textxlabel[2];
                    $xlabel[$idxlabel[3]]  = $textxlabel[3];
                    $xlabel[$idxlabel[4]]  = $textxlabel[4];
                    $xlabel[$idxlabel[5]]  = $textxlabel[5];
                    $xlabel[$idxlabel[6]]  = $textxlabel[6];
                    $xlabel[$idxlabel[7]]  = $textxlabel[7];
                    $xlabel[$idxlabel[8]]  = $textxlabel[8];
                    $xlabel[$idxlabel[9]]  = $textxlabel[9];
                    $xlabel[$idxlabel[10]] = $textxlabel[10];
                    $xlabel[$idxlabel[11]] = $textxlabel[11];
                    $xlabel[$idxlabel[12]] = $textxlabel[12];
                    $xlabel[$idxlabel[13]] = $textxlabel[13];
                break;
        }
        return $xlabel;
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
            if($inidata == 'alltemp'){
            $esql .= "req_temp AS isidata,temp_1 AS isidata2,temp_2 AS isidata3,temp_3 AS isidata4,temp_4 AS isidata5";
            }else{
            $esql .= "req_temp AS isidata,".$inidata." AS isidata2";
            }
            $esql .= " FROM data_record WHERE kode_perusahaan = '".$id_user."' AND kode_kandang = '".$id_farm."' ";
            $esql .= $esqlperiode;
            $esql .= $esqlgrow;
            $esql .= "ORDER BY date_record ASC";    

        $label = [
            'alltemp' => 'Temperature 1 - 4',
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

        $linelabel[0] = $label['req_temp'];
        if($inidata == 'alltemp'){
            $linelabel[0] = $label['req_temp'];
            $linelabel[1] = $label['temp_1'];
            $linelabel[2] = $label['temp_2'];
            $linelabel[3] = $label['temp_3'];
            $linelabel[4] = $label['temp_4'];
        }else{
            $linelabel[1] = $label[$inidata];
            $linelabel[0] = $label['req_temp'];
        }

        //Data Utama
        $dataprimary1 = $this->db->query($esql)->result();

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
            if($inidata == 'alltemp'){
            $cdata3[] = $value2->isidata3;
            $cdata4[] = $value2->isidata4;
            $cdata5[] = $value2->isidata5;
            }
        }
        $isidatagrafik[0] = $bdata;
        $isidatagrafik[1] = $cdata2;
        if($inidata == 'alltemp'){
        $isidatagrafik[2] = $cdata3;
        $isidatagrafik[3] = $cdata4;
        $isidatagrafik[4] = $cdata5;
        }

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
            $adata[] = ''.$value->growday.' - '.$jam;
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
            'req_windspeed' => 'Wind Speed',
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