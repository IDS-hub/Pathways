<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	private $CI;
	public function __construct(){
        parent::__construct();
		$this->load->library(array('form_validation','session'));
		//$this->CI =& get_instance();
        //$this->CI->load->library('email');
		$this->load->model(array('login_model'));

		$this->load->model('admin_model');
    }
	public function index(){
		$this->load->view('admin/login_template/header');
		$this->load->view('admin/login');
		$this->load->view('admin/login_template/footer');
	}
	public function forgetPassword(){
		$this->load->view('admin/login_template/header');
		$this->load->view('admin/forgetPassword');
		$this->load->view('admin/login_template/footer');
	}

	function encrypt_decrypt($action, $string) {
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
	public function loginchk(){
          $username = $this->input->post("txt_username");
          $password = $this->input->post("txt_password");

          $this->form_validation->set_rules("txt_username", "Username", "trim|required");
          $this->form_validation->set_rules("txt_password", "Password", "trim|required");

          if ($this->form_validation->run() == FALSE){
               //$data["title"] ="SmartAgent";
			    $logdata['msg1'] = "Please provide username & password.";
				$this->load->view('admin/login_template/header');
				$this->load->view('admin/login', $logdata);
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
                        //redirect("admin/diagnosisrequest");
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

	public function random_digit($length){
 		$key = '';
 		$keys = range(0, 9);
 		for ($i = 0; $i < $length; $i++) {
 			$key .= $keys[array_rand($keys)];
 		}
 		return $key;
 	}

	public function frgtPass(){
		$username = trim($this->input->post("txt_username"));
		$this->form_validation->set_rules("txt_username", "Username", "trim|required");

		if ($this->form_validation->run() == FALSE){
			$messge = array('message' => 'Please enter correct username.');
			$this->session->set_flashdata('item',$messge );
			redirect($this->config->item('base_url').'admin/login/forgetPassword');
		}else{
			if ($this->input->post('btn_forget') == "Submit"){
				$usr_result = $this->login_model->get_forget_password($username);
				if(count($usr_result)>0){
					$userId = $usr_result->id;
					$userName = $usr_result->first_name;
					$userEmail = $usr_result->email;
					//$password = $usr_result->password;
					//$password = $this->encrypt_decrypt('decrypt', $password);
					//echo  'Hi '.$userName.", Your Password is '".$password."'";

					//$usersEntity = $this->Users->get($user->id);
					$rnd = $this->random_digit(6);
					$valchk = $this->login_model->rand_reset($userId,$rnd);
                    if($valchk==1){
						$config2 = array();
						$config2['useragent'] = 'codeigniter';
						$config2['protocol']  = 'smtp';
						$config2['smtp_host'] = 'ssl://smtp.gmail.com';
						$config2['smtp_port']  = '465';
						$config2['smtp_user']  = 'no-reply@isisdsn.net';
						$config2['smtp_pass']  = 'isis1234';
						$config2['mailtype'] = 'html';
						$config2['charset']  = 'iso-8859-1';
						$config2['wordwrap'] = true;
						$this->load->library('email');
						$this->email->initialize($config2);
						$this->email->set_newline("\r\n");
						$this->email->from('admin@gmail.com','Pathways');
						$this->email->to($userEmail);
						//$this->email->to('hellosanatroy@gmail.com');
						//$this->email->cc('sanat@radikal-labs.com');
						$this->email->subject('Forget Password');
						$msg = '<html><body>';
						$msg .= '<h3 style="color:#f40;">Hi '.$userName.'!</h3>';
						$msg .= '<p>We received a request to reset the password associated with this email address. If you made this request, please follow the instructions below.</p>

			                <p>If you did not request to have your password reset, you can safely ignore this email. We assure you that your customer account is safe.</p>
			                <p><strong>Click the "Reset Password" button below to reset your password:</strong></p>';
							$msg .= '<p>
			                    <a href="http://ec2-34-195-90-14.compute-1.amazonaws.com/Pathways/admin/reset/index/'.$rnd.'" style="width:170px; height:32px; display:inline-block; text-align:center; border-radius:3px; line-height:32px; font-size:18px; font-family:Trebuchet MS, Arial, Helvetica, sans-serif; text-decoration:none; color:#FFF; background:#222;">Reset Password</a>
			                </p>
			                <p>Warm Regards <br> Pathways Team</p>';
						//$msg .= '<p style="color:#080;font-size:18px;">Your password is: '.$password.'</p>';
						$msg .= '</body></html>';
						$this->email->message($msg);

						if (!$this->email->send()){
							//echo $this->email->print_debugger();
							$messge =$this->email->print_debugger();
						}else{
							$messge = array('message' => 'Please check your email.');
						}
						$this->session->set_flashdata('item', $messge);
					}else{
						$messge = array('message' => 'Please try again.');
						$this->session->set_flashdata('item', $messge);
					}
				}else{
					$messge = array('message' => 'Please enter correct username.');
					$this->session->set_flashdata('item', $messge);
				}
				$this->load->view('admin/login_template/header');
				$this->load->view('admin/forgetPassword');
				$this->load->view('admin/login_template/footer');
			}else{

			}
		}
	}

	 public function logout(){
		if($this->session->userdata('userid')!=''){
			$this->session->set_userdata('userid','');
			$this->session->set_userdata('fullname', '');
			$this->session->set_userdata('username', '');
			$this->session->set_userdata('loginuser', FALSE);
			$this->session->set_userdata('msg', '');
			//session_start();
    		session_destroy();
    		unset($_SESSION);
    		//session_regenerate_id(true);
			redirect('admin/login','refresh');
		}else{
			//$this->session->set_userdata('error_msg', "Please login first");
			//redirect($this->config->item('base_url').'admin/login');
			//redirect('admin/home','refresh');
		}
	 }



}
