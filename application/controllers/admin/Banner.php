<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->model('banner_model');
		$this->load->model('admin_model');
		$this->load->library('pagination');
                 $this->load->library('aws_sdk');
    }

	public function index(){

		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'banner';
			$post=$this->input->post();
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);

			$banner_seg5 = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;
			$banner_aid = ($this->uri->segment(4)!='')?$this->uri->segment(4):1;

			if($this->input->post('txtSearch')!=''){
				$data['txtSearch']=$this->input->post('txtSearch');
			}else{
				$data['txtSearch']='';
			}
			//print_r($sess);
			//print_r($post);

			if($this->session->userdata('sv_bannerpageid') == ''){
				$this->session->set_userdata('sv_bannerpageid', 10);
			}
			$config = array();
			$config["base_url"] = $this->config->item('base_url').'admin/banner/index/'.$banner_aid;
			$config["total_rows"] = $this->banner_model->record_count($post,$banner_aid);
			$config["per_page"] = $this->session->userdata('sv_bannerpageid');
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
			$page = $banner_seg5;
			$data["pagination"] = $this->pagination->create_links();
			$data['tmpdetails'] = $this->banner_model->get_banner_data($config["per_page"], $page,$post);

			$data['action'] = base_url().'admin/banner/index';
        	$this->load->view('admin/banner', $data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function setpagination(){
		$this->session->set_userdata('sv_bannerpageid', $this->input->post('pageid'));
		echo 1;
		die();
	}

	public function banneraddedit(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'banner';
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
			$post=$this->input->post();
			$nid = $this->uri->segment(4);
			$data=array();
			if($nid!=''){
				$data['uid'] = $nid;
				$data['tmpdetails'] = $this->banner_model->record_edit($nid);
			}
			//print_r($data); die();
			//$this->load->view('admin/main_template/header', $hdata);
			$this->load->view('admin/banneraddedit',$data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function add(){
		$data['banner_url'] = $this->input->post('banner_url');
                $this->load->library('aws_sdk');
		if($_FILES['banner_file']['name']!=''){
			$config['upload_path'] = './uploads/bannerImage/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$file_name = $_FILES['banner_file']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('banner_file')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$imgdata = array('upload_data' => $this->upload->data());
                                $imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/bannerImage',$_FILES['banner_file']);
				$data['banner_img'] = $imgdata['upload_data']['file_name'];
			}
		}
		$this->banner_model->record_add($data);
		redirect('admin/banner','refresh');
	}

	public function update(){
		//print_r($this->input->post()); die();
		$hid = $this->input->post('banner_id');
		$data['banner_url'] = $this->input->post('banner_url');
		$data['status'] = $this->input->post('status');


		$info = $this->banner_model->tblRec('banner_tbl',$hid);
		if($_FILES['banner_file']['name']!=''){
			$config['upload_path'] = './uploads/bannerImage/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp|svg';
			$file_name = $_FILES['banner_file']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('banner_file')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$old_image=$info->banner_img;
				if (file_exists('./uploads/bannerImage/'.$old_image) && $old_image!=''){
					unlink('./uploads/bannerImage/'.$old_image);
				}
                               $this->aws_sdk->deleteImage($old_image);
				$imgdata = array('upload_data' => $this->upload->data());
                                $imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/bannerImage',$_FILES['banner_file']);
				$data['banner_img'] = $imgdata['upload_data']['file_name'];
				//  $this->load->view('upload_success', $data);
			}
		}
		$this->banner_model->record_update($data,$hid);
		redirect('admin/banner','refresh');
	}

   public function bannerDelete($id = null) {
      //echo 	$id; die();
	  $this->load->model("banner_model");
      $this->banner_model->banner_delete_row($id);
	  redirect('admin/banner','refresh');
   }


}
