<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
        parent::__construct();
		//$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('html');
                $this->load->helper('url');
		$this->load->library(array('form_validation','session'));
		$this->load->model(array('user_model','login_model','admin_model'));
		$this->load->library('pagination');
    }

	public function index(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'user';
			$post=$this->input->post();
			$sess=$this->session->userdata();

			$seg5 = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;
			$aid = ($this->uri->segment(4)!='')?$this->uri->segment(4):1;
			$this->session->set_userdata('is_active', $aid);
			//print_r($sess);
			//print_r($post);
			if($this->input->post('txtSearch')!=''){
				$data['txtSearch']=$this->input->post('txtSearch');
				$aid = $this->input->post('selVal');
				//$this->session->set_userdata('txtSearch', $data['txtSearch']);
				//$this->session->userdata('txtSearch');
				//$this->session->set_userdata('txtSearch', '');
			}else{
				$data['txtSearch']='';
			}
			if($this->input->post('selVal')!=''){
				$data['selVal']= $this->input->post('selVal');
			}else{
				$data['selVal']= 1;
			}

			if($this->session->userdata('sv_userpageid') == ''){
				$this->session->set_userdata('sv_userpageid', 10);
			}

			$config = array();
	        //$config["base_url"] = $this->config->item('base_url').'admin/user/index/'.$this->uri->segment(3);
			$config["base_url"] = $this->config->item('base_url').'admin/user/index/'.$aid;
			$config["total_rows"] = $this->user_model->record_count($post,$aid);
                        $config["per_page"] = $this->session->userdata('sv_userpageid');
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
			$page = $seg5;
			$data["pagination"] = $this->pagination->create_links();
			$condition=array('is_active' => $aid);
			$data['tmpdetails'] = $this->user_model->get_user_data($config["per_page"], $page, $aid, $post);

			$data['action'] = base_url().'admin/user/index';
			$data['pg_start'] = $page+1;
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
                        $this->load->view('admin/user', $data);
			$this->load->view('admin/main_template/footer');
		}
	}


        public function useraddedit(){
                //echo '<pre>'; print_r($this->input->post()); die();
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'user';
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
			$post=$this->input->post();
			$nid = $this->uri->segment(4);
			$data=array();
			if($nid!=''){
				$data['uid'] = $nid;
				$data['tmpdetails'] = $this->user_model->record_edit($nid);
			}
			//print_r($data); die();
			$this->load->view('admin/useraddedit',$data);
			$this->load->view('admin/main_template/footer');
		}
	}


        public function update(){
		$hid = $this->input->post('user_id');
		$data['first_name'] = $this->input->post('first_name');
		$data['last_name'] = $this->input->post('last_name');
		$data['email'] = $this->input->post('email');
		$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Notification updated successfully.</div>');
		$this->user_model->record_update($data,$hid);
		redirect('admin/user/index/1','refresh');
	}


        public function freeaccess($id){
		$this->user_model->get_user_freeaccess($id);
		redirect(base_url().'admin/user/index/'.$this->session->userdata('is_active'));
	}



	public function changeStatus($id){
		$this->user_model->get_user_status($id);
		redirect(base_url().'admin/user/index/'.$this->session->userdata('is_active'));
	}

	public function setpagination(){
		$this->session->set_userdata('sv_userpageid', $this->input->post('pageid'));
		echo 1;
		die();
	}

	public function userAdd(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'user';
			$data['userlist'] = $this->user_model->get_userlist();
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
			$this->load->view('admin/userAdd',$data);
			$this->load->view('admin/main_template/footer');
		}
	}
	public function userexec(){
		$user_id = $this->input->post('user_id');
		$earn = $this->input->post('earn');
		$this->user_model->set_earn($user_id,$earn);
		redirect("admin/user/index/1");
	}




}
