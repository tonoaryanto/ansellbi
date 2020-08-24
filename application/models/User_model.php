<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function json() {
        $this->datatables->select('data_operator.id, data_operator.userlogin, data_operator.username, user.nama_user as nama_farm');
        $this->datatables->from('data_operator');
        //add this line for join
        $this->datatables->join('user', 'data_operator.id_user = user.id');
        return $this->datatables->generate();
    }

}