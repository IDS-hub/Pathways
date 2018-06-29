<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
class App extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library(array('form_validation','session'));
		$this->load->model('login_model');
		$this->load->library('stream');
    }

	public function getSessionId(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			$res['session_id']=$this->stream->getSessionId();
		}
		else{			
			$res['errorcode']     = 1;
			$res['message'] 	  = "Token Not Found.";
		}

		echo json_encode($res);
	}


	public function getGenerateToken()
	{
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			if(!empty($_POST)) 
			{
				$session_id=$this->input->post('session_id');
				$token_type=$this->input->post('token_type');
				if(!isset($session_id) || !isset($token_type))
				{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Invaild Input Parameter";
					echo json_encode($res);
					exit;
				}
				
				$res['token']=$this->stream->getGenerateToken($session_id,$token_type);
				$res['errorcode']     = 0;
			}
			else{			
				$res['errorcode']     = 1;
				$res['message'] 	  = "Invaild Method";
			}			
		}
		else{			
			$res['errorcode']     = 1;
			$res['message'] 	  = "Token Not Found.";
		}

		echo json_encode($res);
	}


	public function getJwtToken(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			$res['jwt_token']=$this->stream->getJWTToken();
		}
		else{			
			$res['errorcode']     = 1;
			$res['message'] 	  = "Token Not Found.";
		}

		echo json_encode($res);
	}


	public function getStreamUrl($session_id){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			$res['stream_url']=$this->stream->getStreamUrl($session_id);
		}
		else{			
			$res['errorcode']     = 1;
			$res['message'] 	  = "Token Not Found.";
		}

		echo json_encode($res);
	}



}
