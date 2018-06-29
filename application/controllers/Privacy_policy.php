<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {
	public function index(){
		$hdata['page'] = 'Privacy Policy';
		//$this->load->view('template/header',$hdata);
		$this->load->view('policy');
	}
}
