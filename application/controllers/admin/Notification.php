<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library('session');
		$this->load->model('notification_model');
		$this->load->model('admin_model');
		$this->load->library('pagination');
    }

	public function index(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'notification';
			$post=$this->input->post();
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);

			$notify_seg5 = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;
			$notify_aid = ($this->uri->segment(4)!='')?$this->uri->segment(4):1;

			if($this->input->post('txtSearch')!=''){
				$data['txtSearch']=$this->input->post('txtSearch');
			}else{
				$data['txtSearch']='';
			}
			//print_r($sess);
			//print_r($post);

			if($this->session->userdata('sv_notifypageid') == ''){
				$this->session->set_userdata('sv_notifypageid', 10);
			}
			$config = array();
			$config["base_url"] = $this->config->item('base_url').'admin/notification/index/'.$notify_aid;
			$config["total_rows"] = $this->notification_model->record_count($post,$notify_aid);
			$config["per_page"] = $this->session->userdata('sv_notifypageid');
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
			$page = $notify_seg5;
			$data["pagination"] = $this->pagination->create_links();
			$data['tmpdetails'] = $this->notification_model->get_notify_data($config["per_page"], $page,$post);
			$data['pg_start'] = $page+1;
			$data['action'] = base_url().'admin/notification/index';
        	$this->load->view('admin/notification', $data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function setpagination(){
		$this->session->set_userdata('sv_notifypageid', $this->input->post('pageid'));
		echo 1;
		die();
	}

	public function notifyaddedit(){
		if(!$this->session->userdata('userid')){
			redirect('admin/login','refresh');
		}else{
			$hdata['active'] = 'notification';
			$hdata['header'] = $this->admin_model->getUserDeatils($this->session->userdata('userid'));
			$this->load->view('admin/main_template/header', $hdata);
			$post=$this->input->post();
			$nid = $this->uri->segment(4);
			$data=array();
			if($nid!=''){
				$data['uid'] = $nid;
				$data['tmpdetails'] = $this->notification_model->record_edit($nid);
			}
			//print_r($data); die();
			$this->load->view('admin/notifyaddedit',$data);
			$this->load->view('admin/main_template/footer');
		}
	}

	public function add(){
		$data['title'] = $this->input->post('title');
		$data['message'] = $this->input->post('message');
		$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		$data['datetime'] = $date->format('Y-m-d H:i:s');
		//$data['status'] = $this->input->post('status');
		$this->notification_model->record_add($data);
		$this->pushMessage();
		$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Notification sent successfully.</div>');
		redirect('admin/notification','refresh');
	}

	public function update(){
		$hid = $this->input->post('notify_id');
		$data['title'] = $this->input->post('title');
		$data['message'] = $this->input->post('message');
		$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		$data['datetime'] = $date->format('Y-m-d H:i:s');
		//$data['status'] = $this->input->post('status');
		$this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Notification updated successfully.</div>');
		$this->notification_model->record_update($data,$hid);
		redirect('admin/notification','refresh');
	}

	public function pushMessage(){
		$result = $this->notification_model->allUser();
		//echo '<pre>';
		//print_r($result);
		$msg = 'Admin Notification';
		if(count($result) > 0){
			foreach($result as $val){
				$res2=array();
				$res2['user_details']['id'] = $val['user_id'];
				$res2['user_details']['first_name'] = $val['first_name'];
				$res2['user_details']['last_name'] = $val['last_name'];
				$res2['user_details']['mob_no'] = $val['mob_no']; $val['profile_image'];
				$res2['user_details']['profile_image'] = ($val['profile_image']!='')?$this->config->item('base_url').'uploads/user/'.$val['profile_image']:$val['profile_image2'];

				if($val['device_type']=='ios'){
					$this->iosPush($val['device_token'], $msg, $res2);
				}else if($val['device_type']=='android'){
					$this->androidPush($val['device_token'], $msg, $res2);
				}
			}
		}
	}

	public function iosPush($token, $msg, $res2){
                //echo "xcvxcvxvxcvxcvxcvc"; die();
		$message = $msg;
		//$token = '570283502268555E07478AEF5B8E793CCEBDDE7502AA63CF0D25396B67FA6279';
		$deviceToken = $token;
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', '/var/www/html/pushcertVisu.pem');
		$ipn_server = "ssl://gateway.sandbox.push.apple.com:2195";
		$fp = stream_socket_client($ipn_server,$err,$errstr,60,STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,$ctx);
		$sid = 4;
		$body['aps'] = array(
		    //'badge' => +1,
		    'alert' => $message,
		    'sound' => 'default',
			'info' => ''
		);
		//$body['streamId'] = $sid;
		$payload = json_encode($body);
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		$result = fwrite($fp, $msg, strlen($msg));
		@socket_close($fp);
		fclose($fp);
	}

	public function androidPush($token, $msg, $res2){
		set_time_limit(0);
			$device_token = $token;
           	$registrationIds = $device_token;
			$msg3 = array(
				'body' 	=> $msg,
				'title'	=> 'VisuLive',
				'vibrate'	=> 1,
				'sound'		=> 1,
				'largeIcon'	=> 'large_icon',
				'smallIcon'	=> 'small_icon'
				//'info' => $res2
	        );
			$fields3 = array(
				'to'		=> $registrationIds,
				'notification'	=> $msg3,
				'data' => $res2
				//'data' => ''
			);
			$headers = array(
               'Authorization: key=' . API_ACCESS_KEY,
               'Content-Type: application/json'
            );
			//print_r($headers); die();
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields3 ) );
			$result = curl_exec($ch);
			//echo $result;
			if($result === false)
        	die('Curl failed ' . curl_error());
			curl_close( $ch );
			//exit();
	}






}
