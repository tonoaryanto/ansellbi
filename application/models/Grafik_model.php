<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grafik_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function json_day($val1,$val2,$val3,$val4,$val5,$val6,$val7){
        $this->datatables->select("id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,FORMAT(isi_value,2) AS isi_value");
        $this->datatables->from('image2');
        $this->datatables->where([
            'kategori' => $val1,
            'nama_data' => $val2,
            'kode_kandang' => $val3,
            'kode_perusahaan' => $val4,
            'periode' => $val7,
        ]);
        $this->datatables->where("grow_value BETWEEN '".$val5."' AND '".$val6."'");
        return $this->datatables->generate();
    }
    function json_hour($val1,$val2,$val3,$val4,$val5,$val7){
        $this->datatables->select("id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,FORMAT(isi_value,2) AS isi_value");
        $this->datatables->from('image2');
        $this->datatables->where([
            'kategori' => $val1,
            'nama_data' => $val2,
            'kode_kandang' => $val3,
            'kode_perusahaan' => $val4,
            'periode' => $val7,
            'grow_value' => $val5
        ]);
        return $this->datatables->generate();
    }
    function json_hour2($val1,$val2,$val3,$val4,$val5,$val7,$val22,$val23,$val27){
        $this->datatables->select("inidata.id, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS tanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jam_value, inidata.isi_value as isi_value1,image2.isi_value as isi_value3,inidata.grow_value");
        $this->datatables->from("(SELECT id,tanggal_value,jam_value,grow_value,FORMAT(isi_value,2) AS isi_value FROM `image2` WHERE kode_perusahaan = '".$val4."' AND nama_data = '".$val2."' AND kategori = '".$val1."' AND kode_kandang = '".$val3."' AND periode = '".$val7."' AND grow_value = '".$val5."' ORDER BY tanggal_value, jam_value ASC) inidata, image2");
        $this->datatables->where("image2.jam_value = inidata.jam_value AND image2.grow_value = inidata.grow_value");
        $this->datatables->where([
            'image2.nama_data' => $val27,
            'image2.kode_perusahaan' => $val4,
            'image2.kategori' => $val1,
            'image2.kode_kandang' => $val22,
            'image2.periode' => $val23,
        ]);
        $this->datatables->group_by('inidata.jam_value');
        $data = ['0','2','3','1','2','3','1','2','3'];
        $this->datatables->add_column('isi_value2', '$1',$data[1]);
        return $this->datatables->generate();
    }
    function json_day2($val1,$val2,$val3,$val4,$val5,$val6,$val7,$val22,$val23,$val27){
        $this->datatables->select("inidata.id, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS tanggal_value, CONCAT(LPAD(SUBSTRING_INDEX(image2.jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(image2.jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(image2.jam_value, '-', -1), 2, '0')) AS jam_value, inidata.isi_value as isi_value1,image2.isi_value as isi_value2,inidata.grow_value");
        $this->datatables->from("(SELECT id,tanggal_value,jam_value,grow_value,FORMAT(isi_value,2) AS isi_value FROM `image2` WHERE kode_perusahaan = '".$val4."' AND nama_data = '".$val2."' AND kategori = '".$val1."' AND kode_kandang = '".$val3."' AND periode = '".$val7."' AND grow_value BETWEEN '".$val5."' AND '".$val6."' ORDER BY tanggal_value, jam_value ASC) inidata, image2");
        $this->datatables->where("image2.grow_value = inidata.grow_value");
        $this->datatables->where([
            'image2.nama_data' => $val27,
            'image2.kode_perusahaan' => $val4,
            'image2.kategori' => $val1,
            'image2.kode_kandang' => $val22,
            'image2.periode' => $val23,
        ]);

        $this->datatables->group_by('inidata.grow_value');
        return $this->datatables->generate();
    }

}