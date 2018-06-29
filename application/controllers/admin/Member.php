<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct(){
        parent::__construct();
		//$this->load->library('session');
		$this->load->library(array('form_validation','session'));
		$this->load->model('member_model');
		$this->load->model('admin_model');
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

	public function index(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{

			$data['view_data']= $this->member_model->get_admin_data();
			$data['view_data']['password'] = $this->encrypt_decrypt('decrypt', $data['view_data']['password']);
			//print_r($data);die();
			$data['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header',$data);
        	        $this->load->view('admin/member');
			$this->load->view('admin/main_template/footer');
		}
	}

	public function update(){
                 
		//echo '<pre>'; print_r($this->input->post()); die();
		//echo $this->input->post("password");
		$first_name = $this->input->post("first_name");
		$last_name = $this->input->post("last_name");
		$email = $this->input->post("email");
		$mob_no = $this->input->post("mob_no");
		//$password = $this->encrypt_decrypt('encrypt', $this->input->post("password"));
		$hid = $this->input->post("id");

		$info = $this->member_model->tblRec('admin_tbl',$hid);
                //echo '<pre>'; print_r($info); die();
                //echo '<pre>'; print_r($_FILES['member_file']['name']); die();
		if($_FILES['member_file']['name']!=''){
                        //echo "hhhhhhhhh"; die();
			$config['upload_path'] = './uploads/user/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$file_name = $_FILES['member_file']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('member_file')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$old_image=$info->member_img;
				if (file_exists('./uploads/user/'.$old_image) && $old_image!='' && isset($old_image)){
					unlink('./uploads/user/'.$old_image);
				}
                                echo '<pre>'; print_r($this->upload->data()); 
				$imgdata = array('upload_data' => $this->upload->data());
                                //echo '<pre>'; print_r($imgdata); die();
				$member_img = $imgdata['upload_data']['file_name'];
				//  $this->load->view('upload_success', $data);
			}
		}
                //echo "ggggggggggggg"; die();
		//$this->form_validation->set_rules("first_name", "First Name", "trim|required");
		$this->form_validation->set_rules("first_name", "First Name", "trim|required");
		$this->form_validation->set_rules("last_name", "Last Name", "trim|required");
		//$this->form_validation->set_rules("email", "Email", "trim|required");
		$this->form_validation->set_rules("mob_no", "Phone Number", "trim|required|min_length[10]|max_length[15]|regex_match[/^[0-9]+$/]");
		//$this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');

	    if ($this->form_validation->run() == FALSE){
                        //echo "False"; die();
			$data['view_data']= $this->member_model->get_admin_data();
			//$data['view_data']['password'] = $this->encrypt_decrypt('decrypt', $data['view_data']['password']);
			$data['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header',$data);
        	        $this->load->view('admin/member');
			$this->load->view('admin/main_template/footer');

	    } else {
                        //echo "True"; die();
			$this->session->set_flashdata('message_name', '<div class="alert alert-success text-center">Profile updated successfully.</div>');
			$usr_result = $this->member_model->get_admin_update($first_name, $last_name, $email, $mob_no,$member_img, $hid);
			//echo '<pre>'; printr($usr_result); die();
                        redirect('admin/member');
	    }
	}

	public function resetPassword(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$data = [];
			$data['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header',$data);
        	$this->load->view('admin/resetPassword');
			$this->load->view('admin/main_template/footer');
		}
	}

	public function updatePassword(){
		if($this->session->userdata('userid')){
			//echo $password = $this->input->post("new_admin_pwd");//$this->encrypt_decrypt('encrypt', );
			//echo $password = $this->input->post("confirm_password");
			$hid = $this->session->userdata('userid');
			$this->form_validation->set_rules("first_name", "First Name", "trim|required");
			$this->form_validation->set_rules("last_name", "Last Name", "trim|required");
			if ($this->form_validation->run() == FALSE){


		    } else {
				$this->session->set_flashdata('message_name', '<div class="alert alert-success text-center">Profile updated successfully.</div>');
				$usr_result = $this->member_model->get_admin_update($first_name, $last_name, $email, $mob_no,$member_img, $hid);
				redirect('admin/member');
		    }
		}else{
			redirect('admin/login','refresh');
		}
	}

	public function confirmPassword(){
		if($this->session->userdata('userid')){
			//$pst = $this->input->post();
			$password = $this->input->post("new_admin_pwd");
			$password = $this->encrypt_decrypt('encrypt', $password);
			$hid = $this->session->userdata('userid');
			$this->session->set_flashdata('message_name', '<div class="alert alert-success text-center">Password updated successfully.</div>');
			$usr_result = $this->member_model->get_admin_password_update($password, $hid);
			redirect('admin/member');
			//print_r($pst);
			//die();
		}else{
			redirect('admin/login','refresh');
		}
	}



}
