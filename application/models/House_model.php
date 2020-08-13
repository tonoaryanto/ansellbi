<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class House_model extends CI_Model
{
    public $table = 'absensi';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json($ini) {
        $this->datatables->select('id, nama_kandang');
        $this->datatables->from('data_kandang');
        $this->datatables->where("kode_perusahaan = '".$ini."'");
        //add this line for join
        // $this->datatables->join('pegawai', 'absensi.id_pegawai = pegawai.id');
        return $this->datatables->generate();
    }

}