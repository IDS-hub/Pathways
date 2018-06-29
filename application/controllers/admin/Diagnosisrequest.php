<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Diagnosisrequest extends CI_Controller {
	public function __construct(){
        parent::__construct();
		//$this->load->library('session');
		$this->load->library(array('form_validation','session'));
		$this->load->model('diagnosisrequest_model');
		$this->load->model('admin_model');
		$this->load->library('pagination');
    }

	public function index(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'Diagnosisrequest';
			$post=$this->input->post();
			$sess=$this->session->userdata();
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);

			$incm_seg5 = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;
			$incm_aid = ($this->uri->segment(4)!='')?$this->uri->segment(4):0;
			$this->session->set_userdata('status', $incm_aid);

			if($this->input->post('txtSearch')!=''){
				$data['txtSearch']=$this->input->post('txtSearch');
			}else{
				$data['txtSearch']='';
			}
			if($this->input->post('selVal')!=''){
				$data['selVal']= $this->input->post('selVal');
			}else{
				$data['selVal']= $incm_aid;
			}
			//print_r($sess);
			//print_r($post);

			if($this->session->userdata('sv_incomepageid') == ''){
				$this->session->set_userdata('sv_incomepageid', 10);
			}
			$config = array();
			$config["base_url"] = $this->config->item('base_url').'admin/diagnosisrequest/index/'.$incm_aid;
			$config["total_rows"] = $this->diagnosisrequest_model->record_count($post,$incm_aid);
			$config["per_page"] = $this->session->userdata('sv_incomepageid');
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
			$page = $incm_seg5;
			$data["pagination"] = $this->pagination->create_links();
			$data['tmpdetails111'] = $this->diagnosisrequest_model->get_diagnosisrequest_data($config["per_page"], $page, $incm_aid, $post);
			$data['pg_start'] = $page+1;
			//echo '<pre>'; print_r($data['tmpdetails']); die();
			$temp = array_unique(array_column($data['tmpdetails111'], 'diagnosis_request_title'));
			$data['tmpdetails'] = array_intersect_key($data['tmpdetails111'], $temp);
			//echo '<pre>'; print_r($unique_arr); die();

			$data['action'] = base_url().'admin/diagnosisrequest/index/'.$incm_aid;
        	$this->load->view('admin/diagnosisrequest', $data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function setpagination(){
		$this->session->set_userdata('sv_incomepageid', $this->input->post('pageid'));
		echo 1;
		die();
	}

	public function removeurl(){
		$sid = $this->uri->segment(4);
		$feature_id = $this->uri->segment(5);
		$this->stream_model->remove_url($sid);
		redirect('admin/stream/index/'.$feature_id,'refresh');
	}

	public function changeStatus($id){
		$this->diagnosisrequest_model->get_user_status($id);
		//redirect(base_url().'admin/diagnosisrequest/index/'.$this->session->userdata('status'));
		redirect(base_url().'admin/diagnosisrequest/index');
	}

	public function delete_click_approve($id) {
		//echo $id; die();
		$diagnosis_requestInfo = $this->diagnosisrequest_model->diagnosis_requestInfo($id);
		//echo '<pre>'; print_r($diagnosis_requestInfo); die();
		$data['title'] = $diagnosis_requestInfo->diagnosis_request_title;
		$data['posx'] = 0;
		$data['posy'] = -1;
		$data['posz'] = 2;
		$data['rotx'] = 0;
		$data['roty'] = 0;
		$data['rotz'] = 0;
		$data['fov'] = 50;
		$data['targetBone'] = 'pelvis';
		$this->db->insert('diagnosis_tbl', $data);

		$this->db->select('*')->where(array('id'=>$id));
		$this->db->delete('diagnosis_request_tbl');
		redirect(base_url().'admin/diagnosisrequest/index');

	}

}
