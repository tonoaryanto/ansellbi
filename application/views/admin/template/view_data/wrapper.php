<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->konfigurasi->cek_akses($this->uri->segment(1));
require_once('header.php');
require_once('navbar.php');
require_once('content.php');
require_once('footer.php');
?>