<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Signup extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model(array('login_model','frntlogin_model'));
		$this->load->library('email');
	}
	
	public function verification()
	{
		$viewdata['mainheader']='Pathways';
		$viewdata['subheader']='Verification';
		$viewdata['header_content']='';
		
		$checkvericode=$this->login_model->check_verification($this->uri->segment(3));
		$viewdata['msg']=($checkvericode != '') ? 'SUCCESS' : 'ERROR';
		$viewdata['type']='VERIFICATION';
		//$this->layout_login->view('/signup/confirm',$viewdata);
		$this->load->view('/signup/confirm', $viewdata);
	}
	
	public function confirm()
	{
		$viewdata['mainheader']='Gov365';
		$viewdata['subheader']='Confirmation';
		$viewdata['header_content']='';
		$viewdata['msg']='SUCCESS';
		$viewdata['type']=($this->uri->segment(3) == 'linkedin') ? 'LINKEDIN' : 'CONFIRM';
		$this->layout_login->view('/signup/confirm',$viewdata);
	}
	
}

?>