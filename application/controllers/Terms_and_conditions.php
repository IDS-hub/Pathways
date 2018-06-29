<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Terms_and_conditions extends CI_Controller {
	/*public function __construct(){
        parent::__construct();
    }*/
	public function index(){
		$hdata['page'] = 'Terms And Conditions';
		//$this->load->view('template/header',$hdata);
		$this->load->view('terms');
	}
}
