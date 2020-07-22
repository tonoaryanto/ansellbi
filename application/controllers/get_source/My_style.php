<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My_style extends CI_Controller {

	public function index(){
		redirect('error_408');
	}

	public function css($get = null){
		$ini = explode('-',explode('.', $get)[0]);

		$url = $ini[0]; 
		for ($i=1; $i < count($ini); $i++) { $url .= '/'.$ini[$i];}

		header("Content-Type: text/css; charset: UTF-8");

		if ($get == null) {echo 'SILENT IS GOLD';}
		else {$this->load->view($url);}
	}

	public function js($get = null){
		$ini = explode('-',explode('.', $get)[0]);

		$url = $ini[0];
		for ($i=1; $i < count($ini); $i++) {$url .= '/'.$ini[$i];}

		header("Content-Type: application/javascript");
		header("Cahce-Control: max-age=604800, public");		

		if ($get == null) {echo 'SILENT IS GOLD';}
		else {$this->load->view($url);}
	}
}
