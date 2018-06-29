<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library(array('form_validation','session'));
		$this->load->model(array('login_model'));
		//$this->load->library('session');
    }

	public function index($rnd=null){
		//print_r($this->input->post());
		$message = 0;
		if($this->input->post('btn_reset') == "Reset"){
			$new_password = $this->input->post("new_password");
            //$con_password = $this->input->post("con_password");
			$hidnum = $this->input->post("hidnum");
			$pass = $this->encrypt_decrypt('encrypt',$new_password);
			$chk = $this->login_model->password_chk($pass,$hidnum);
			if($chk===1){
				$this->session->set_flashdata('msg2', 'Thank You! Your password reset.');
				redirect('admin/login');
			}else{
				$this->session->set_flashdata('msg2', 'Please try again.');
				redirect('admin/login/forgetPassword');
			}
		}else{
			if($this->login_model->checkRand($rnd)){
				$message = 1;
			}
		}
		$data = array("message"=>$message,"rnd"=>$rnd);
		$this->load->view('admin/login_template/header');
		$this->load->view('admin/reset',$data);
		$this->session->set_userdata('varify', $rnd);
		$this->load->view('admin/login_template/footer');
	}

	public function encrypt_decrypt($action, $string) {
	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'This is my secret key';
	    $secret_iv = 'This is my secret iv';
	    // hash
	    $key = hash('sha256', $secret_key);
	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	    if( $action == 'encrypt' ) {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    }
	    else if( $action == 'decrypt' ){
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }
	    return $output;
	}

	public function passwordchk(){
          $new_password = $this->input->post("new_password");
          $con_password = $this->input->post("con_password");

          $this->form_validation->set_rules("txt_username", "Username", "trim|required");
          $this->form_validation->set_rules("txt_password", "Password", "trim|required");

          if ($this->form_validation->run() == FALSE){
               //$data["title"] ="SmartAgent";
			    $logdata['msg1'] = "Please provide new password & confirm password.";
				$this->load->view('admin/login_template/header');
				$this->load->view('admin/reset', $logdata);
				$this->load->view('admin/login_template/footer');
          }
          else{
               if ($this->input->post('btn_login') == "Login"){
					$password = $this->encrypt_decrypt('encrypt', $password);
                    $usr_result = $this->login_model->get_login($username, $password);
                    if ($usr_result > 0){
						$this->session->set_userdata('userid', $usr_result->id);
						$this->session->set_userdata('fullname', $usr_result->first_name.' '.$usr_result->last_name);
						$this->session->set_userdata('username', $usr_result->username);
						$this->session->set_userdata('type', $usr_result->type);
						$this->session->set_userdata('loginuser', TRUE);
                        redirect("admin/user/index/1");
                    }
                    else{
                         $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid username and password!</div>');
                         redirect('admin/login');
                    }
               }
               else{
                    redirect('admin/login');
               }
          }
	 }








}
