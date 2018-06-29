<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->model('quiz_model');
		$this->load->model('admin_model');
		$this->load->library('pagination');
		$this->load->library('aws_sdk');
    }

	public function index(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'quiz';
			$post=$this->input->post();
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);

			$gift_seg5 = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;
			$gift_aid = ($this->uri->segment(4)!='')?$this->uri->segment(4):1;

			if($this->input->post('txtSearch')!=''){
				$data['txtSearch']=$this->input->post('txtSearch');
			}else{
				$data['txtSearch']='';
			}
			//print_r($sess);
			//print_r($post);

			if($this->session->userdata('sv_giftpageid') == ''){
				$this->session->set_userdata('sv_giftpageid', 10);
			}
			$config = array();
			$config["base_url"] = $this->config->item('base_url').'admin/quiz/index/'.$gift_aid;
			$config["total_rows"] = $this->quiz_model->record_count($post,$gift_aid);
			$config["per_page"] = $this->session->userdata('sv_giftpageid');
			$config["uri_segment"] = 5;
			$config['num_links'] = 1;
			$config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
			$config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='active'><span>";
			$config['cur_tag_close'] = "</span></li>";
			$config['prev_link'] = '&laquo;';
			$config['next_link'] = '&raquo;';
			//print_r($config);
			$this->pagination->initialize($config);
			$page = $gift_seg5;
			$data["pagination"] = $this->pagination->create_links();
			$data['tmpdetails'] = $this->quiz_model->get_gift_data($config["per_page"], $page,$post);

			$data['action'] = base_url().'admin/quiz/index';
        	$this->load->view('admin/quiz', $data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function setpagination(){
		$this->session->set_userdata('sv_giftpageid', $this->input->post('pageid'));
		echo 1;
		die();
	}

	public function quizaddedit(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'quiz';
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
			$post=$this->input->post();
			$nid = $this->uri->segment(4);
			$data=array();
			if($nid!=''){
				$data['uid'] = $nid;
				$data['tmpdetails1'] = $this->quiz_model->record_edit($nid);
			}
			//echo '<pre>'; print_r($data); die();
			$this->load->view('admin/quizaddedit',$data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function add(){
		$data['title'] = $this->input->post('title');
		$data['coin_amt'] = $this->input->post('coin_amt');
		$this->load->library('aws_sdk');
		//$data['status'] = '0';
		if($_FILES['icon_file']['name']!=''){
			$config['upload_path'] = './uploads/giftImage/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$file_name = $_FILES['icon_file']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('icon_file')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$imgdata = array('upload_data' => $this->upload->data());
				$imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/giftImage',$_FILES['icon_file']);
				$data['gift_img'] = $imgdata['upload_data']['file_name'];
			}
		}
		$this->gift_model->record_add($data);
		redirect('admin/gift','refresh');
	}

	public function update(){
		//print_r($this->input->post()); //die();
		$hid = $this->input->post('gift_id');
		//echo $hid; die();
		$data['question'] = $this->input->post('question');
		$data['correctAnswer'] = $this->input->post('correctAnswer');
		$data['annotation'] = $this->input->post('annotation');
		$info = $this->quiz_model->tblRec('session_tbl',$hid);
		//echo '<pre>'; print_r($info); die();
		/***if($_FILES['icon_file']['name']!=''){
			$config['upload_path'] = './uploads/quizImage/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$file_name = $_FILES['icon_file']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('icon_file')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$old_image=$info->session_summary_image;
				if (file_exists('./uploads/quizImage/'.$old_image) && $old_image!=''){
					unlink('./uploads/quizImage/'.$old_image);
				}
				$this->aws_sdk->deleteImage($old_image);
				$imgdata = array('upload_data' => $this->upload->data());
				$imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/quizImage',$_FILES['icon_file']);
				$data['session_summary_image'] = $imgdata['upload_data']['file_name'];
				//  $this->load->view('upload_success', $data);
			}
		}*****/

		/***if($_FILES['icon_file_audio']['name']!=''){
			$config['upload_path'] = './uploads/audio_files_aws/';
			//$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$config['allowed_types'] = 'ogg';
			//$config['max_size']     = '10000';

			$file_name = $_FILES['icon_file_audio']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			//print_r($config); die();

			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('icon_file_audio')){
				//echo "Not Uploaded"; die();
				$error = array('error' => $this->upload->display_errors());
				//print_r($error); die();
			}else{
				$old_image=$info->audio_url;
				//echo $old_image; die();

				if (file_exists('./uploads/audio_files_aws/'.$old_image) && $old_image!=''){
					unlink('./uploads/audio_files_aws/'.$old_image);
				}
				$this->aws_sdk->deleteImage($old_image);
				$imgdata = array('upload_data' => $this->upload->data());
				$imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/audio_files_aws',$_FILES['icon_file_audio']);
				$data['audio_url'] = $imgdata['upload_data']['file_name'];
				//  $this->load->view('upload_success', $data);
			}
		}***/

		$this->quiz_model->record_update($data,$hid);
		redirect('admin/quiz','refresh');
	}

}
