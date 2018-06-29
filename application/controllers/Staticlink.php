<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(0);
class Staticlink extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$this->load->view('staticlink');
	}
}
