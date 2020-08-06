<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Farm_model extends CI_Model
{
    public $table = 'absensi';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function json() {
        $this->datatables->select('id, nama_user As nama_farm, alamat_user As alamat_farm');
        $this->datatables->from('user');
        // $this->datatables->where("keterangan = 'customer'");
        //add this line for join
        // $this->datatables->join('pegawai', 'absensi.id_pegawai = pegawai.id');
        return $this->datatables->generate();
    }

}