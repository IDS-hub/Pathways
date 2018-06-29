<?php 
require(APPPATH . "/libraries/vendor/Twilio/autoload.php"); // Loads the library for twilio
use Twilio\Rest\Client;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('stream');
		$this->load->library('aws_sdk');
		//$this->load->library('twilio');
		$this->load->model(array('login_model','stream_model','frntlogin_model'));
	}

	function random_string($length){
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		$key .=	date('njs');
		return $key;
	}
	function random_digit($length){
		$key = '';
		$keys = range(0, 9);
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		return $key;
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

	public function signUp(){
		$res=array();
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$data['password'] 		= $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		$data['mob_no'] 		= $this->input->post('mob_no');
		//$data['reference_id'] 		= $this->input->post('reference_id');

		$dvc_data['device_token'] 	= $this->input->post('device_token');
		$dvc_data['device_type'] 	= $this->input->post('device_type');

		if(!isset($dvc_data['device_type']) || !isset($dvc_data['device_token']))
		{
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}
		/*$plain_txt = "Hello World$%@";
		echo "Plain Text = $plain_txt\n";
		$encrypted_txt = $this->encrypt_decrypt('encrypt', $plain_txt);
		echo "Encrypted Text = $encrypted_txt\n";
		$decrypted_txt = $this->encrypt_decrypt('decrypt', $encrypted_txt);
		echo "Decrypted Text = $decrypted_txt\n";
		die();*/

		$this->form_validation->set_rules('first_name','First Name','trim|required');
		//$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');
		$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^\+?[0-9]{10,14}$/]');

		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			if($this->login_model->isValideMobile($data) > 0){
				$res['res'] = '';
				$res['errorcode']   = 1;
				$res['message']     = "The mobile number you entered is already registered.";
			}else{
				$dvc_data['access_token']=$this->random_string(16);
				$data['activation_code']=$this->random_digit(6);

				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['created'] = $time;

				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] 	= $lastInsert;
				$this->db->insert('device_tbl', $dvc_data);

				//$res['res']   = 0;
				//$res['access_token']    = $dvc_data['access_token'];

				$res['res'] = array('activation_code' => $data['activation_code']);
				$res['errorcode']  = 0;
				$res['message'] = "Thank you for register. Please verify your mobile number for next step";
			}
		}
		echo json_encode($res);
	}

	public function newSignUp(){
		$res=array();
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$data['password'] 		= $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		$data['mob_no'] 		= $this->input->post('mob_no');
		$data['country_code']   = $this->input->post('country_code');
		$data['gender'] 		= $this->input->post('gender');
		$data['dob'] 			= $this->input->post('dob'); //Y-m-d
		//$data['reference_id'] 		= $this->input->post('reference_id');

		$dvc_data['device_token'] 	= $this->input->post('device_token');
		$dvc_data['device_type'] 	= $this->input->post('device_type');

		if(!isset($dvc_data['device_type']) || !isset($dvc_data['device_token']))
		{
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}

		$this->form_validation->set_rules('first_name','First Name','trim|required');
		//$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');
		$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^\+?[0-9]{10,12}$/]');

		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			// if($this->login_model->isValideMobile($data) > 0){
			// 	$res['res'] = '';
			// 	$res['errorcode']   = 1;
			// 	$res['message']     = "The mobile number you entered is already registered.";
			// }else{
				$dvc_data['access_token']=$this->random_string(16);
				$data['user_code']=$this->random_digit(6);

				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['created'] = $time;


				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] 	= $lastInsert;
				$this->db->insert('device_tbl', $dvc_data);
				//$res['res'] = array('activation_code' => $data['activation_code']);
				$res['errorcode']  = 0;
				$res['message'] = "Thank you for registration";
			//}

			$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
			$rest['id'] = $result->id;
			$rest['first_name'] = $result->first_name;
			$rest['last_name'] = $result->last_name;
			$rest['gender'] = $result->gender;
			$rest['dob'] = $result->dob;
			$rest['age'] = $this->ageCal($result->dob);
			$rest['mob_no'] = $result->mob_no;
			$rest['coins_earned'] = $result->coins_earned;
			$rest['coins_spent'] = $result->coins_spent;
			$rest['coins_withdrawn'] = $result->coins_withdrawn;
			$rest['fans'] = (string)$this->stream_model->fanfunc($result->id);
			$rest['following'] = (string)$this->login_model->followingfunc('follower_tbl',$result->id);
			$rest['follower'] = (string)$this->login_model->followerfunc('follower_tbl',$result->id);
			$rest['profile_image'] = ($result->profile_image!='')?$result->profile_image:'';
			$rest['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
			$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id),array(),array(),0,0,0);

			if(count($result2) > 0){
				$rest['token'] = $result2->access_token;
			}else{
				$this->db->select('*')->where(array('user_id'=>$result->id));
				$this->db->delete('device_tbl');
				$access_token = $this->random_string(16);
				$pdata = array('user_id' => $result->id,'access_token' => $access_token);
				$str = $this->db->insert('device_tbl', $pdata);
				$rest['token'] = $access_token;
			}
			$res['res'] = $rest;
		}
		echo json_encode($res);
	}

	public function ageCal($Bday){
		$today = new DateTime();
		$diff = $today->diff(new DateTime($Bday));
		return (($diff->y)>0)?$diff->y:0;
	}

	public function login(){
		$data['mob_no']       = $this->input->post('mob_no');
		$data['password']     = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		$data['device_type']  = $this->input->post('device_type');
		$data['device_token'] = $this->input->post('device_token');
		$res=array();
		$res['res'] = '';
		/*if(!isset($data['device_type'])){
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Device Type";
			echo json_encode($res);
			exit;
		}
		else if(!isset($data['device_token'])){
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Device Token";
			echo json_encode($res);
			exit;
		}*/
		//$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^\+?[0-9]{10,12}$/]');
		$this->form_validation->set_rules('mob_no','Mobile Number','required');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('device_type','Device Type','required');
		$this->form_validation->set_rules('device_token','Device Token','required');

		if($this->form_validation->run() === FALSE){
			$res['errorcode'] = 1;
			$res['message']  = "Please provide all parameters";
		}else{
			$result = $this->login_model->get_full_details('user_tbl','*',array('mob_no' => $data['mob_no'],'password'=>$data['password']),array(),array(),0,0,0);

			if(count($result) > 0){
				if($result->is_active=='1'){
					$rest['id'] = $result->id;
					//$rest['reference_id'] = $result->reference_id;
					$rest['user_code'] = ($result->user_code!='')?$result->user_code:'';
					$rest['first_name'] = $result->first_name;
					$rest['last_name'] = $result->last_name;
					$rest['mob_no'] = $result->mob_no;
					$rest['gender'] = $result->gender;
					$rest['dob'] = $result->dob;
					$rest['age'] = $this->ageCal($result->dob);
					$rest['coins_earned'] = $result->coins_earned;
					$rest['coins_spent'] = $result->coins_spent;
					$rest['coins_withdrawn'] = $result->coins_withdrawn;
					$rest['fans'] = (string)$this->stream_model->fanfunc($result->id);
					$rest['following'] = $this->login_model->followingfunc('follower_tbl',$result->id);
					$rest['follower'] = $this->login_model->followerfunc('follower_tbl',$result->id);
					$rest['profile_image'] = ($result->profile_image!='')?$result->profile_image:$result->profile_image2;
					$rest['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
					$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id,'device_token'=>$data['device_token'],'device_type'=>$data['device_type']),array(),array(),0,0,0);

					if(count($result2) > 0){
						$rest['token'] = $result2->access_token;
						$this->login_model->webLogDel($result->id);
					}else{
						//$this->db->select('*')->where(array('device_type' => $data['device_type'],'user_id'=>$result->id));
						$this->db->select('*')->where(array('user_id'=>$result->id));
						$this->db->delete('device_tbl');
						$access_token = $this->random_string(16);
						$pdata = array('device_type' => $data['device_type'], 'device_token' => $data['device_token'],'user_id' => $result->id,'access_token' => $access_token);
						$str = $this->db->insert('device_tbl', $pdata);
						$rest['token'] = $access_token;
					}
					//if(isset($result->id) && $result->id!=''){
						//$this->login_model->webLogDel($result->id);
					//}
					$res['res'] = $rest;
					$res['errorcode']     = 0;
					$res['message'] 	  = "Success";
				}else {
					$res['errorcode']     = 1;
					$res['message'] 	  = "Status Inactive";
				}
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "The mobile number or password you entered is incorrect";
			}
		}
		echo json_encode($res);
	}
	public function varify(){
		$data['mob_no'] = $this->input->post('mob_no');
		$data['activation_code'] = trim($this->input->post('activation_code'));
		$res=array();
		$res['res'] = '';
		$result3 = $this->login_model->get_full_details('user_tbl','*',array('mob_no' => $data['mob_no']),array(),array(),0,0,0);
		$active_code = $result3 -> activation_code;
		if($active_code==$data['activation_code']){
			$data = array('is_active' => '1');
			$this->db->where('id', $result3->id);
			$this->db->update('user_tbl',$data);

			$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $result3->id),array(),array(),0,0,0);
			$rest['id'] = $result->id;
			$rest['user_code'] = ($result->user_code!='')?$result->user_code:'';
			$rest['first_name'] = $result->first_name;
			$rest['last_name'] = $result->last_name;
			$rest['mob_no'] = $result->mob_no;
			$rest['coins_earned'] = $result->coins_earned;
			$rest['coins_spent'] = $result->coins_spent;
			$rest['coins_withdrawn'] = $result->coins_withdrawn;
			$rest['fans'] = (string)$this->stream_model->fanfunc($result->id);
			$rest['following'] = (string)$this->login_model->followingfunc('follower_tbl',$result->id);
			$rest['follower'] = (string)$this->login_model->followerfunc('follower_tbl',$result->id);
			$rest['profile_image'] = ($result->profile_image!='')?$result->profile_image:'';
			$rest['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
			$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id),array(),array(),0,0,0);

			if(count($result2) > 0){
				$rest['token'] = $result2->access_token;
			}else{
				$this->db->select('*')->where(array('user_id'=>$result->id));
				$this->db->delete('device_tbl');
				$access_token = $this->random_string(16);
				$pdata = array('user_id' => $result->id,'access_token' => $access_token);
				$str = $this->db->insert('device_tbl', $pdata);
				$rest['token'] = $access_token;
			}
			$res['res'] = $rest;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Successfully Active";
		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Code";
		}
		echo json_encode($res);
	}

	public function search(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$search_val = strtolower(trim($this->input->post('search_val')));
			$limit = GLOBAL_LIMIT;
			$limit_start = $this->input->post('page');
			$uid = $accessToken->user_id;
			$result = $this->login_model->get_search_res($uid,$search_val,$limit,$limit_start);
			//echo '<pre>'; print_r($result); die();
                        if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['user_code'] = ($val['user_code']!='')?$val['user_code']:'';
					$res2[$p]['first_name'] = $val['first_name'];
					$res2[$p]['last_name'] = $val['last_name'];
					$res2[$p]['mob_no'] = $val['mob_no'];
					$res2[$p]['gender'] = $val['gender'];
					$res2[$p]['dob'] = $val['dob'];
					$ag = strlen((string)$this->ageCal($val['dob']));
					$res2[$p]['age'] = ($ag>2)?0:$this->ageCal($val['dob']);
					$res2[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:$val['profile_image2'];
					$res2[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res2[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res2[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$res2[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res2[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res2[$p]['no_of_fan'] = (string)$this->stream_model->fanfunc($val['id']);
					$totalAmount = $val['coins_earned']  - ($val['coins_spent'] + $val['coins_withdrawn']);
					$res2[$p]['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2[$p]['location'] = ($val['location']!='')?$val['location']:'';
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function addFollower(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//print_r($accessToken); die();
			$data['created_by_user_id'] = $accessToken->user_id;
			$data['follower_id'] = $this->input->post('follower_id');
			$result = $this->login_model->get_full_details('follower_tbl','*',array('created_by_user_id' => $data['created_by_user_id'],'follower_id' => $data['follower_id']),array(),array(),0,0,0);
			if(count($result)<1){
				$this->db->insert('follower_tbl', $data);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Followed Successfully";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "Already Followed";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function following(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$data['follow'] = $this->input->post('follow');
			$limit = GLOBAL_LIMIT;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->folrec('follower_tbl',$data['follow'],$limit,$limit_start);
			$result3 = $this->login_model->getUserName($data['follow']);
			$res1['id']= $result3->id;
			$res1['first_name']= $result3->first_name;
			$res1['last_name']= $result3->last_name;
			$res1['mob_no']= $result3->mob_no;
			$res1['profile_image'] = ($result3->profile_image!='')?$result3->profile_image:'';
			$res1['created']=date("d/m/Y h:ia",strtotime($result2->created));
			$res2=array();
			if(count($result)>0){
				foreach ($result as $key => $value) {
					$result2=$this->login_model->getUserName($value['created_by_user_id']);
					$res2[$key]['id']= $result2->id;
					$res2[$key]['first_name']= $result2->first_name;
					$res2[$key]['last_name']= $result2->last_name;
					$res2[$key]['mob_no']= $result2->mob_no;
					$res2[$key]['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:'';
					$res2[$key]['created']=date("d/m/Y h:ia",strtotime($result2->created));
				}
				$res1['created_by_user']=$res2;
			}else{
				$res1['created_by_user']=$res2;
			}
			$res['res'] = $res1;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function followMe(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			//$data['created_by_user_id'] = $this->input->post('created_by_user_id');


		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function unFollow(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$data['created_by_user_id'] = $accessToken->user_id;
			$data['follower_id'] = $this->input->post('follower_id');
			$result = $this->login_model->unFollow('follower_tbl',array('created_by_user_id' => $data['created_by_user_id'],'follower_id' => $data['follower_id']));
			if($result!=0){
				$res['errorcode']     = 0;
				$res['message'] 	  = "Unfollowed Successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Followed Successfully";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamingList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$limit = GLOBAL_LIMIT;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->streamrec('stream_tbl','',$limit,$limit_start,$uid);
                        //echo '<pre>'; print_r($result); die();
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$result2 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$val['created_by_user_id']));
					$res2=array();
					$res2['id'] = $result2->id;
					$res2['user_code'] = ($result2->user_code!='')?$result2->user_code:'';
					$res2['first_name'] = $result2->first_name;
					$res2['last_name'] = $result2->last_name;
					$res2['mob_no'] = $result2->mob_no;
					$res2['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
					$res2['cover_image'] = ($result2->cover_image!='')?$result2->cover_image:'';
					$res2['is_fan'] = (string)$this->login_model->chk_fan($uid,$val['created_by_user_id']);
					$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
					$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2['no_of_fan'] = (string)$this->stream_model->fanfunc($result2->id);
					$res2['is_follow'] = $this->login_model->chk_follower($uid,$result2->id);
					$res3[$p]['id'] = $val['id'];
					//$res3[$p]['tokbox_session_id'] = $val['tokbox_session_id'];
					$res3[$p]['stream_url'] = $val['stream_url'];
					//$res3[$p]['broadcast_id'] = $val['broadcast_id'];
					// $res3[$p]['view_cnt'] = $val['view_cnt'];
                                        $res3[$p]['view_cnt'] = $this->login_model->getTableCount('stream_participants_tbl',array('stream_id' =>$val['id'],'is_active'=>1));
					$res3[$p]['location'] = $val['location'];
					$res3[$p]['user_details'] = $res2;
					$res3[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res3[$p]['like'] = $this->login_model->stream_like_yes_no($uid,$val['id']);
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function featuredStreamList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//$data['featured'] = $this->input->post('featured');
			$limit = GLOBAL_LIMIT;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->streamrec('stream_tbl',1,$limit,$limit_start,$uid);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$result2 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$val['created_by_user_id']));
					$res2=array();
					$res2['id'] = $result2->id;
					$res2['user_code'] = ($result2->user_code!='')?$result2->user_code:'';
					$res2['first_name'] = $result2->first_name;
					$res2['last_name'] = $result2->last_name;
					$res2['mob_no'] = $result2->mob_no;
					$res2['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
					$res2['cover_image'] = ($result2->cover_image!='')?$result2->cover_image:'';
					$res2['is_fan'] = (string)$this->login_model->chk_fan($uid,$val['created_by_user_id']);
					$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
					$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2['no_of_fan'] = (string)$this->stream_model->fanfunc($result2->id);
					$res2['is_follow'] = $this->login_model->chk_follower($uid,$result2->id);
					$res3[$p]['id'] = $val['id'];
					$res3[$p]['tokbox_session_id'] = $val['tokbox_session_id'];
					$res3[$p]['stream_url'] = $val['stream_url'];
					$res3[$p]['broadcast_id'] = $val['broadcast_id'];
					$res3[$p]['view_cnt'] = $val['view_cnt'];
					$res3[$p]['location'] = $val['location'];
					$res3[$p]['user_details'] = $res2;
					$res3[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res3[$p]['like'] = $this->login_model->stream_like_yes_no($uid,$val['id']);
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function createStreamList(){
		$accessToken = $this->login_model->tokenAccess();
		$uid = $accessToken->user_id;
		$res=array();
		if(count($accessToken) > 0){
			$rnd_str = $this->random_string(16);
			//$data['is_featured'] = ($this->input->post('featured')=='')?'0':$this->input->post('featured');
			$data['is_featured'] = '0';
			$data['location'] = $this->input->post('location');
			$data['created_by_user_id'] = $uid;
			$data['stream_url'] = $rnd_str;
			//$data['tokbox_session_id'] = $this->stream->getSessionId();
			$rnd = $this->random_string(16);
			$data['broadcast_id'] = $rnd;
			// if($this->input->post('featured')==1){
			// 	$chkPoint = (int)$this->login_model->chkPoint($uid);
			// 	if($chkPoint==0){
			// 		$res['errorcode']     = 1;
			// 		$res['message'] 	  = "Coins is not sufficient";
			// 		echo json_encode($res);
			// 		exit;
			// 	}
			// 	$this->login_model->reduceCoinEarn($uid);
			// }
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
			$data['created'] = $time;

			$this->db->insert('stream_tbl', $data);
			$insert_id = $this->db->insert_id();

			/*$result4 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$uid));
			$first_name = $result4->first_name;
			$msgg = $first_name.' is going to be live.';
			$res2 ='';
			$ress = $this->login_model->pushMessgging($uid);
			if(count($ress)>0){
				foreach ($ress as $val) {
					$this->androidPush($val['device_token'], $msgg, $res2);
				}
			}*/
			$res['errorcode']     = 0;
			$res['broadcast_id'] = $rnd;
			//$res['tokbox_session_id'] = $data['tokbox_session_id'];
			//$res['tokbox_token']= $this->stream->getGenerateToken($data['tokbox_session_id'],'moderator');
			$res['stream_id']= $insert_id;
			$res['message'] 	  = "Successfully Added";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function saveStreamUrl($id,$tokbox_session_id)
	{
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$data['id']=$id;
			$stream=json_decode($this->stream->getStreamUrl($tokbox_session_id));
			$data['stream_url']=$stream->broadcastUrls->hls;
			$data['broadcast_id']=$stream->id;
			$result = $this->login_model->updateStream('stream_tbl',$id,$data);
			if($result!=0){
				$this->login_model->updatePoint($uid,2);  //starts a stream
				$res['stream_url']=$stream->broadcastUrls->hls;
				$res['broadcast_id']=$stream->id;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Updated Successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Record Not Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function stopBrodcasting()
	{
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$tokbox_brodcast_id = $this->input->post('brodcast_id');
			//$data['id'] = $this->input->post('stream_id');
			$id = $this->input->post('stream_id');

			//$stream=json_decode($this->stream->stopBrodcasting($tokbox_brodcast_id));
			//print_r($stream); die();
			//echo $stream->status; die();

			if($tokbox_brodcast_id != '') //$stream->status == 'stopped'
			{
				$data['stream_url']='';
				$data['broadcast_id']='';

				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['updated'] = $time;

				$result = $this->login_model->updateStream('stream_tbl',$id,$data);

				if($result!=0){
					$res['errorcode']     = 0;
					$res['message'] 	  = "Stop Brodcast Successfully";
					$this->login_model->count_stream_like($uid,$id);
				}else{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Record Not Found";
				}
			}
			else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Error in Brodcast Stopping";
			}

		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function stopBrodcastingIos()
	{
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			//$tokbox_brodcast_id = $this->input->post('brodcast_id');
			//$data['id'] = $this->input->post('stream_id');
			$id = $this->input->post('stream_id');

			//$stream=json_decode($this->stream->stopBrodcasting($tokbox_brodcast_id));

			if($id != ''){
				$data['stream_url']='';
				$data['broadcast_id']='';

				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['updated'] = $time;

				$result = $this->login_model->updateStream('stream_tbl',$id,$data);

				if($result!=0){
					$res['errorcode']     = 0;
					$res['message'] 	  = "Stop Brodcast Successfully";
					$this->login_model->count_stream_like($uid,$id);
				}else{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Record Not Found";
				}
			}
			else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Error in Brodcast Stopping";
			}

		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function stop_Brodcasting_Android()
	{
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			//$tokbox_brodcast_id = $this->input->post('brodcast_id');
			//$data['id'] = $this->input->post('stream_id');
			$id = $this->input->post('stream_id');

			//$stream=json_decode($this->stream->stopBrodcasting($tokbox_brodcast_id));

			if($id != ''){
				$data['stream_url']='';
				$data['broadcast_id']='';

				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['updated'] = $time;

				$result = $this->login_model->updateStream('stream_tbl',$id,$data);

				if($result!=0){
					$res['errorcode']     = 0;
					$res['message'] 	  = "Stop Brodcast Successfully";
					$this->login_model->count_stream_like($uid,$id);
				}else{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Record Not Found";
				}
			}
			else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Error in Brodcast Stopping";
			}

		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamEndDetl(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$sid = $this->input->post('stream_id');
			//$tokbox_session_id = $this->input->post('tokbox_session_id');
			$result = $this->login_model->stream_end_detl($sid);
			$res['res']     =$result;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function addFan(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$stream_id = $this->input->post('stream_id');
			$data['fan_id'] = $this->input->post('fan_id');
			$data['created_by_user_id'] = $accessToken->user_id;
			$result = $this->login_model->get_full_details('fan_tbl','*',array('created_by_user_id' => $data['created_by_user_id'],'fan_id' => $data['fan_id']),array(),array(),0,0,0);
			if(count($result)<1){
				$this->db->insert('fan_tbl', $data);
				if(isset($stream_id) && $stream_id!=''){
					$this->login_model->update_stream_fan($stream_id);
				}
				$res['errorcode']     = 0;
				$res['message'] 	  = "Successfully Added";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "Already Added";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function fanList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$limit = GLOBAL_LIMIT;
			$data['fan_id'] = $this->input->post('fan_id');
			$limit_start = $this->input->post('page');

			$result3 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$data['fan_id']));
			$res3['fan_id'] = $data['fan_id'];
			$res3['fan_first_name'] = $result3->first_name;
			$res3['fan_last_name'] = $result3->last_name;
			$res3['fan_mob_no'] = $result3->mob_no;
			$res3['profile_image'] = ($result3->profile_image!='')?$result3->profile_image:$result3->profile_image2;

			$result = $this->login_model->fanrec('fan_tbl',$data['fan_id'],$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach ($result as $key => $val) {
					$result2 = $this->login_model->fanInfo('user_tbl',array('id' =>$val['created_by_user_id']));
					$res2=array();
					$res2['id'] = $result2->id;
					$res2['first_name'] = $result2->first_name;
					$res2['last_name'] = $result2->last_name;
					$res2['mob_no'] = $result2->mob_no;
					$res2['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
					$res3['user_details'][$p] = $res2;
					$p++;
				}
			}else{
				$res3['user_details'] = array();
			}
			$res['res'] = $res3;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function editProfile(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$id = $accessToken->user_id;
			$info = $this->login_model->userRec('user_tbl',$id);
			$config['upload_path'] = './uploads/user/';
			$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
			$file_name = $_FILES['profile_image']['name'];
			$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
			$config['file_name'] = time().$file_name;
			$this->load->library('upload');
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('profile_image')){
				$error = array('error' => $this->upload->display_errors());
			 	//	print_r($error);
				//  $this->load->view('upload_form', $error);
			}else{
				$old_image=$info->profile_image;
				if (file_exists('./uploads/user/'.$old_image)){
					unlink('./uploads/user/'.$old_image);
				}
				$imgdata = array('upload_data' => $this->upload->data());
				$imgdata['upload_data']['file_name'] = $this->aws_sdk->sendFile('uploads/user',$_FILES['profile_image']);
				$data['profile_image'] = $imgdata['upload_data']['file_name'];
				//  $this->load->view('upload_success', $data);
			}
			$config1['upload_path'] = './uploads/coverImage/';
			$config1['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
			$file_name1 = $_FILES['cover_image']['name'];
			$file_name1 = preg_replace(FILENAME_PATTERN,'_',$file_name1);
			$config1['file_name'] = time().$file_name1;
			$this->load->library('upload');
			$this->upload->initialize($config1);
			if ( ! $this->upload->do_upload('cover_image')){
				$error1 = array('error' => $this->upload->display_errors());
			}else{
				$old_image1=$info->cover_image;
				if (file_exists('./uploads/coverImage/'.$old_image1)){
					unlink('./uploads/coverImage/'.$old_image1);
				}
				$imgdata1 = array('upload_data1' => $this->upload->data());
				$imgdata1['upload_data1']['file_name'] = $this->aws_sdk->sendFile('uploads/coverImage',$_FILES['cover_image']);
				$data['cover_image'] = $imgdata1['upload_data1']['file_name'];
			}
			$data['first_name'] = !empty($this->input->post('first_name'))?$this->input->post('first_name'):$info->first_name;
			$data['last_name'] = !empty($this->input->post('last_name'))?$this->input->post('last_name'):$info->last_name;
			$data['mob_no'] = !empty($this->input->post('mob_no'))?$this->input->post('mob_no'):$info->mob_no;
			$data['dob'] = !empty($this->input->post('dob'))?$this->input->post('dob'):$info->dob;
			$data['gender'] = !empty($this->input->post('gender'))?$this->input->post('gender'):$info->gender;
			$result = $this->login_model->updateProfile('user_tbl',$id,$data);
			if($result != 0){
				$info = $this->login_model->userRec('user_tbl',$id);
				$rec = array();
				$rec['id'] = $info->id;
				$rec['first_name'] = $info->first_name;
				$rec['last_name'] = $info->last_name;
				$rec['mob_no'] = $info->mob_no;
				$originalDate = $info->dob;
				//$res2['dob'] = date("d-m-Y", strtotime($originalDate));
				$rec['dob'] = date("d-m-Y", strtotime($originalDate));
				$rec['dob1'] = date("Y-m-d", strtotime($originalDate));
				$rec['gender'] = $info->gender;
				$rec['profile_image'] = ($info->profile_image!='')?$info->profile_image:$info->profile_image2;
				$rec['cover_image'] = ($info->cover_image!='')?$info->cover_image:'';
				$rec['token'] = $accessToken->access_token;
				$res['res'] = $rec;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Successfully Updated";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "Not Updated";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function participateToStream(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			if(!empty($_POST)){
				$data['stream_id']= $this->input->post('stream_id');
				$data['role_type']= $this->input->post('role_type');
				$result1=$this->login_model->get_stream_rec($data['stream_id']);
                                //echo $result1; die();
				if(count($result1)>0){ //ARIJIT  && $data['role_type']=='subscriber'
					//$data['tokbox_session_id'] = $result1->tokbox_session_id;
					$data['stream_url'] = $result1->stream_url;
					$data['created_by_user_id'] = $accessToken->user_id;
					$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
					$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
					$data['created'] = $time;
                                        $data['is_active'] = 1;  //Added by Rakesh on 19.01.2018//
					//$participant_cnt = $this->login_model->get_participant($result1->tokbox_session_id,$accessToken->user_id);
					$participant_cnt = $this->login_model->get_participant($data['stream_id'],$accessToken->user_id);
					//echo $participant_cnt; 
                                        //echo '<pre>'; print_r($data); die();
                                        if($participant_cnt<1){ //$participant_cnt<1
                                                //echo "hhhhhh"; die();
						$this->db->insert('stream_participants_tbl', $data);
						$viewCnt = $this->login_model->get_stream_view_cnt($data['stream_id']);
						$this->login_model->update_stream_view_cnt($data['stream_id'],$viewCnt+1);
						$res['errorcode']     = 0;
						$res['message'] 	  = "Successfully Added";
					}else{
                                                //echo "kkkkkk"; die();
						$this->login_model->update_status_participants_stream_rec($data['stream_id'], $uid, 1,$data['role_type']);
						$res['errorcode']     = 0;
						$res['message'] 	  = "Already Added";
					}
				}else{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Stream Id Not Found";
				}
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Invaild Method";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function test(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			$res['errorcode']     = 0;
			$res['message'] 	  = "Token  Found";
		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function getSessionId(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		if(count($accessToken) > 0){
			$res['session_id']=$this->stream->getSessionId();
		}
		else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
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
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
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
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
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
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}

		echo json_encode($res);
	}



	public function removeStreamPartcipants(){
		$res=array();
		$res['res'] = '';
		$accessToken = $this->login_model->tokenAccess();
		if(count($accessToken) > 0){
			$user_id = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$result = $this->login_model->get_participants_stream_rec($stream_id,$user_id);

			if(count($result)>0){
				//$user_id = $accessToken->user_id;
				//$this->login_model->rem_view_cnt($stream_id);

				$this->login_model->update_participants_stream_rec($stream_id,$user_id);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Successfully Deleted";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Record Not Found";
			}
		}
		else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}

		echo json_encode($res);
		exit();
	}

	public function removeStreamPartcipants_Android(){
		$res=array();
		$res['res'] = '';
		$accessToken = $this->login_model->tokenAccess();
		if(count($accessToken) > 0){
			$user_id = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$result = $this->login_model->get_participants_stream_rec($stream_id,$user_id);

			if(count($result)>0){
				//$user_id = $accessToken->user_id;
				//$this->login_model->rem_view_cnt($stream_id);

				$this->login_model->update_participants_stream_rec($stream_id,$user_id);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Successfully Deleted";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Record Not Found";
			}
		}
		else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}

		echo json_encode($res);
		exit();
	}

	public function giftList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$result = $this->login_model->record('gift_tbl');
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res3[$p]['id'] = $val['id'];
					$res3[$p]['title'] = $val['title'];
					$res3[$p]['gift_img'] = ($val['gift_img']!='')?$val['gift_img']:'';
					$res3[$p]['coin_amt'] = $val['coin_amt'];
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function sendGift(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$user_id = $accessToken->user_id;
			$send_user_id = $this->input->post('send_user_id');
			$gift_id = $this->input->post('gift_id');
			$exp = explode(',',$gift_id);
			$total_amt = 0;
			if($exp[0]!='' && count($exp)>0){
				$k=0;
				foreach ($exp as $val) {
					$total_amt += $this->login_model->gift_amt('gift_tbl',$val);
					$giftDtl = $this->login_model->gift_dtl('gift_tbl',$val);
					$res4[$k]['id'] = $giftDtl[0]['id'];
					$res4[$k]['title'] = $giftDtl[0]['title'];
					$res4[$k]['gift_img'] = ($giftDtl[0]['gift_img']!='')?$giftDtl[0]['gift_img']:'';
					$k++;
				}
			}
			$result = $this->login_model->chk_balance('user_tbl',$user_id,$total_amt);
			if($result == 1){
				$this->login_model->add_balance('user_tbl',$user_id,$send_user_id,$total_amt);
				$res['deduction'] = $total_amt;
				$res['res'] = $res4;
				//$this->login_model->updatePoint($send_user_id,3);   //receive gift
				//$this->login_model->updatePoint($user_id,4);	//sends gift
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Money";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function googleLogin(){
		$res=array();
		$res['res'] = '';
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		$data['dob'] 			= $date->format('Y-m-d');
		$data['google_id'] 		= trim($this->input->post('google_id'));
		//$data['profile_image2'] 	= $this->input->post('profile_image');
		$data['profile_image2'] 	= (trim($this->input->post('profile_image')!=''))?$this->input->post('profile_image'):$this->config->item('base_url').'uploads/user/no-image.png';
		$dvc_data['device_token'] 	= $this->input->post('device_token');
		$dvc_data['device_type'] 	= $this->input->post('device_type');

		if(!isset($dvc_data['device_type']) || !isset($dvc_data['device_token'])){
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}
		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('google_id','Google ID','trim|required');

		if($this->form_validation->run() === FALSE){
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			$token = $this->random_string(16);
			$data['user_code']=$this->random_digit(6);
			$dvc_data['access_token'] = $token;
			$gid = $data['google_id'];
			$result = $this->login_model->get_full_details('user_tbl','*',array('google_id' => $data['google_id']),array(),array(),0,0,0);
			if(count($result)<1){
				$data['is_active'] = '1';
				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] = $lastInsert;
				$dvc_data['access_token'] = $token;
				$this->db->insert('device_tbl', $dvc_data);
				$result3 = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
				$rest['id'] = $result3->id;
				$rest['first_name'] = $result3->first_name;
				$rest['last_name'] = $result3->last_name;
				$rest['mob_no'] = ($result3->mob_no!='')?$result3->mob_no:'';
				$rest['coins_earned'] = $result3->coins_earned;
				$rest['coins_spent'] = $result3->coins_spent;
				$rest['coins_withdrawn'] = $result3->coins_withdrawn;
				$rest['fans'] = $this->stream_model->fanfunc($result3->id);
				$rest['following'] = '';
				$rest['follower'] = '';
				$rest['profile_image'] = ($result3->profile_image!='')?$result3->profile_image:$result3->profile_image2;
				$rest['cover_image'] = ($result3->cover_image!='')?$result3->cover_image:'';
				$result4 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $lastInsert,'device_token'=>$dvc_data['device_token'],'device_type'=>$dvc_data['device_type']),array(),array(),0,0,0);
				$rest['token'] = $result4->access_token;
				$res['res'] = $rest;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$rest['id'] = $result->id;
				$rest['first_name'] = $result->first_name;
				$rest['last_name'] = $result->last_name;
				$rest['mob_no'] = ($result->mob_no!='')?$result->mob_no:'';
				$rest['coins_earned'] = $result->coins_earned;
				$rest['coins_spent'] = $result->coins_spent;
				$rest['coins_withdrawn'] = $result->coins_withdrawn;
				$rest['fans'] = $this->stream_model->fanfunc($result->id);
				$rest['following'] = '';
				$rest['follower'] = '';
				$rest['profile_image'] = ($result->profile_image!='')?$result->profile_image:$result->profile_image2;
				$rest['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
				$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id,'device_token'=>$dvc_data['device_token'],'device_type'=>$dvc_data['device_type']),array(),array(),0,0,0);
				if(count($result2) > 0){
					$rest['token'] = $result2->access_token;
				}else{
					$this->db->select('*')->where(array('device_type' => $dvc_data['device_type'],'user_id'=>$result->id));
					$this->db->delete('device_tbl');
					$access_token = $this->random_string(16);
					$pdata = array('device_type' => $dvc_data['device_type'], 'device_token' => $dvc_data['device_token'],'user_id' => $result->id,'access_token' => $access_token);
					$str = $this->db->insert('device_tbl', $pdata);
					$rest['token'] = $access_token;
				}
				$res['res'] = $rest;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}
		}
		echo json_encode($res);
	}

	public function facebookLogin(){
		$res=array();
		$res['res'] = '';
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		$data['dob'] 			= $date->format('Y-m-d');
		$data['facebook_id'] 		= trim($this->input->post('facebook_id'));
		$data['profile_image2'] 	= (trim($this->input->post('profile_image')!=''))?$this->input->post('profile_image'):$this->config->item('base_url').'uploads/user/no-image.png';
		$dvc_data['device_token'] 	= $this->input->post('device_token');
		$dvc_data['device_type'] 	= $this->input->post('device_type');

		if(!isset($dvc_data['device_type']) || !isset($dvc_data['device_token'])){
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}
		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('facebook_id','Facebook ID','trim|required');

		if($this->form_validation->run() === FALSE){
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			$token = $this->random_string(16);
			$data['user_code']=$this->random_digit(6);
			$dvc_data['access_token'] = $token;
			$gid = $data['facebook_id'];
			$result = $this->login_model->get_full_details('user_tbl','*',array('facebook_id' => $data['facebook_id']),array(),array(),0,0,0);
			if(count($result)<1){
				$data['is_active'] = '1';
				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] = $lastInsert;
				$dvc_data['access_token'] = $token;
				$this->db->insert('device_tbl', $dvc_data);
				$result3 = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
				$rest['id'] = $result3->id;
				$rest['first_name'] = $result3->first_name;
				$rest['last_name'] = $result3->last_name;
				$rest['mob_no'] = ($result3->mob_no!='')?$result3->mob_no:'';
				$rest['coins_earned'] = $result3->coins_earned;
				$rest['coins_spent'] = $result3->coins_spent;
				$rest['coins_withdrawn'] = $result3->coins_withdrawn;
				$rest['fans'] = $this->stream_model->fanfunc($result3->id);
				$rest['following'] = '';
				$rest['follower'] = '';
				//$rest['profile_image'] = ($result3->profile_image!='')?$this->config->item('base_url').'uploads/user/'.$result3->profile_image:'';
				$rest['profile_image'] = ($result3->profile_image!='')?$result3->profile_image:$result3->profile_image2;
				$rest['cover_image'] = ($result3->cover_image!='')?$result3->cover_image:'';
				$result4 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $lastInsert,'device_token'=>$dvc_data['device_token'],'device_type'=>$dvc_data['device_type']),array(),array(),0,0,0);
				$rest['token'] = $result4->access_token;
				$res['res'] = $rest;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$rest['id'] = $result->id;
				$rest['first_name'] = $result->first_name;
				$rest['last_name'] = $result->last_name;
				$rest['mob_no'] = ($result->mob_no!='')?$result->mob_no:'';
				$rest['coins_earned'] = $result->coins_earned;
				$rest['coins_spent'] = $result->coins_spent;
				$rest['coins_withdrawn'] = $result->coins_withdrawn;
				$rest['fans'] = $this->stream_model->fanfunc($result->id);
				$rest['following'] = '';
				$rest['follower'] = '';
				$rest['profile_image'] = ($result->profile_image!='')?$result->profile_image:$result->profile_image2;
				$rest['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
				$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id,'device_token'=>$dvc_data['device_token'],'device_type'=>$dvc_data['device_type']),array(),array(),0,0,0);
				if(count($result2) > 0){
					$rest['token'] = $result2->access_token;
				}else{
					$this->db->select('*')->where(array('device_type' => $dvc_data['device_type'],'user_id'=>$result->id));
					$this->db->delete('device_tbl');
					$access_token = $this->random_string(16);
					$pdata = array('device_type' => $dvc_data['device_type'], 'device_token' => $dvc_data['device_token'],'user_id' => $result->id,'access_token' => $access_token);
					$str = $this->db->insert('device_tbl', $pdata);
					$rest['token'] = $access_token;
				}
				$res['res'] = $rest;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}
		}
		echo json_encode($res);
	}

	public function followerStreamList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$limit = GLOBAL_LIMIT;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			//$result = $this->login_model->streamrec('stream_tbl',1,$limit,$limit_start);
			$result = $this->login_model->rec_follewer_id($uid,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$result2 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$val['created_by_user_id']));
					$res2=array();
					$res2['id'] = $result2->id;
					$res2['user_code'] = ($result2->user_code!='')?$result2->user_code:'';
					$res2['first_name'] = $result2->first_name;
					$res2['last_name'] = $result2->last_name;
					$res2['mob_no'] = $result2->mob_no;
					$res2['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
					$res2['cover_image'] = ($result2->cover_image!='')?$result2->cover_image:'';
					$res2['is_fan'] = (string)$this->login_model->chk_fan($uid,$val['created_by_user_id']);
					$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
					$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2['no_of_fan'] = (string)$this->stream_model->fanfunc($result2->id);
					$res2['is_follow'] = $this->login_model->chk_follower($uid,$result2->id);
					$res3[$p]['id'] = $val['id'];
					$res3[$p]['tokbox_session_id'] = $val['tokbox_session_id'];
					$res3[$p]['stream_url'] = $val['stream_url'];
					$res3[$p]['broadcast_id'] = $val['broadcast_id'];
					//$res3[$p]['view_cnt'] = $val['view_cnt'];
                                        //$res3[$p]['view_cnt'] = $val['view_cnt'];
                                        $res3[$p]['view_cnt'] = $this->login_model->getTableCount('stream_participants_tbl',array('stream_id' =>$val['id'],'is_active'=>1));
					$res3[$p]['location'] = $val['location'];
					$res3[$p]['user_details'] = $res2;
					$res3[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res3[$p]['like'] = $this->login_model->stream_like_yes_no($uid,$val['id']);
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function myIncome(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$data['mycoin'] = $this->input->post('mycoin');
			$data['wallet_type'] = $this->input->post('wallet_type');
			$data['mob_no'] = $this->input->post('mob_no');
			$data['bank_name'] = $this->input->post('bank_name');
			$data['acc_no'] = $this->input->post('acc_no');
			$data['acc_holder'] = $this->input->post('acc_holder');
			$data['ifs_code'] = $this->input->post('ifs_code');
			$data['email'] = $this->input->post('email');

			$this->form_validation->set_rules('mycoin','My Coin','trim|required');
			$this->form_validation->set_rules('wallet_type','Wallet Type','trim|required');
			//$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^[0-9]{10,12}$/]');

			if($this->form_validation->run() === FALSE){
				$res['errorcode'] =	1;
				$res['message']="Please provide all parameters";
			}else{
				$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $uid),array(),array(),0,0,0);
				//print_r($result); die();
				$data['first_name'] = $result->first_name;
				$data['last_name'] = $result->last_name;
				$data['user_id'] = $uid;

				$coins_earned = $result->coins_earned;
				$coins_withdrawn = $result->coins_withdrawn+$data['mycoin'];
				$chk = $this->login_model->get_user_earned_chk($uid,$data['mycoin']);
				if($chk==0){
					$this->db->insert('myincome_tbl', $data);
					$this->login_model->get_user_earned_update($uid,$coins_earned,$coins_withdrawn);
					$res['errorcode']     = 0;
					$res['message'] 	  = "Success";
				}else{
					$res['errorcode']     = 1;
					$res['message'] 	  = "Earn is not sufficient";
				}
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function feedback(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$data['type'] = $this->input->post('type');
			$data['email'] = $this->input->post('email');
			$data['desc'] = $this->input->post('desc');
			$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $uid),array(),array(),0,0,0);
			$data['first_name'] = $result->first_name;
			$data['last_name'] = $result->last_name;
			$data['user_id'] = $uid;
			$err_flg = 1;
			$file_name = $_FILES['fdbk_image1']['name'];
			//echo $file_name;die();
			if(isset($file_name) && $file_name!=''){
				$config['upload_path'] = './uploads/feedback/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				$config['max_size']  = '650KB';
				$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
				$config['file_name'] = time().$file_name;
				$this->load->library('upload');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('fdbk_image1')){
					//$error = array('error' => $this->upload->display_errors());
					$err_flg = 0;
				}else{
					$imgdata = array('upload_data' => $this->upload->data());
					$data['fdbk_image1'] = $imgdata['upload_data']['file_name'];
				}
			}

			$file_name2 = $_FILES['fdbk_image2']['name'];
			if(isset($file_name2) && $file_name2!=''){
				$config2['upload_path'] = './uploads/feedback/';
				$config2['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				$config2['max_size']  = '650KB';
				$file_name2 = preg_replace(FILENAME_PATTERN,'_',$file_name2);
				$config2['file_name'] = time().$file_name2;
				$this->load->library('upload');
				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('fdbk_image2')){
					$err_flg = 0;
				}else{
					$imgdata2 = array('upload_data' => $this->upload->data());
					$data['fdbk_image2'] = $imgdata2['upload_data']['file_name'];
				}
			}

			$file_name3 = $_FILES['fdbk_image3']['name'];
			if(isset($file_name3) && $file_name3!=''){
				$config3['upload_path'] = './uploads/feedback/';
				$config3['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				$config3['max_size']  = '650KB';
				$file_name3 = $_FILES['fdbk_image3']['name'];
				$file_name3 = preg_replace(FILENAME_PATTERN,'_',$file_name3);
				$config3['file_name'] = time().$file_name3;
				$this->load->library('upload');
				$this->upload->initialize($config3);
				if (!$this->upload->do_upload('fdbk_image3')){
					$err_flg = 0;
				}else{
					$imgdata3 = array('upload_data' => $this->upload->data());
					$data['fdbk_image3'] = $imgdata3['upload_data']['file_name'];
				}
			}
			if($err_flg!=0){
				$this->db->insert('feedback_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$res['feedback_id']     = $lastInsert;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Feedback submitted successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Feedback submition failed";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function coinPurchase(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$data['user_id'] = $uid;
			$total_amt = 0;
			$purchase_identifier = $this->input->post('purchase_identifier');
			$purchase_id = $this->input->post('purchase_id');
			$type = $this->input->post('type');

			if($purchase_id!=''){
				$total_amt = $this->login_model->purchase_amt('purchase_list_tbl',$purchase_id);
				$total_coin = $this->login_model->purchase_coin('purchase_list_tbl',$purchase_id);
			}
			else if($purchase_identifier!=''){
			 	$purchase_list_id = $this->login_model->find_purchase_id($purchase_identifier,$type);
				$total_amt = $this->login_model->purchase_amt('purchase_list_tbl',$purchase_list_id);
				$total_coin = $this->login_model->purchase_coin('purchase_list_tbl',$purchase_list_id);
			}
			$data['amount'] = $total_amt;
			$data['coin'] = $total_coin;
			$this->db->insert('purchase_tbl', $data);
			$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $uid),array(),array(),0,0,0);
			//$amt = $result->coins_earned+$data['amount'];
			$amt = $result->coins_earned+$total_coin;
			$this->login_model->set_user_coins_earned($uid,$amt);
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function parchaseHistory(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//$limit = GLOBAL_LIMIT;
			$limit = 30;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->parchase_record($uid,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					//$res3[$p]['amount'] = $val['amount'];
					$res3[$p]['amount'] = $val['coin'];
					$res3[$p]['created'] = date("d.m.Y",strtotime($val['created']));
					$p++;
				}
				$prchis_end=0;
				if($p<10){
					$prchis_end=1;
				}
				$res['res'] = $res3;
				$res['prchis_end'] = $prchis_end;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function purchaseList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$limit = GLOBAL_LIMIT;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->parchase_list($limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res3[$p]['pid'] = $val['id'];
					$res3[$p]['coin_no'] = $val['coin'];
                                        $res3[$p]['amount'] = $val['amount'];
                                        
					$res3[$p]['description'] = $val['desc'];
					$res3[$p]['identify'] = $val['identifier'];
					$res3[$p]['pricing'] = $val['amount'];
                                        $res3[$p]['itunes_amount'] = $val['itunes_amount'];
					$res3[$p]['demo_android_identifier'] = $val['demo_android_identifier'];
					$res3[$p]['demo_ios_identifier'] = $val['demo_ios_identifier'];
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function ranking(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//$limit = GLOBAL_LIMIT;
			$limit = 200;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			$type = $this->input->post('type');
			$result = $this->login_model->coin_rank($uid,$limit,$limit_start,$type);
			//print_r($result);
			if(count($result) > 0){
				$res['res'] = $result;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['res'] = "No Record Found.";
				$res['errorcode']     = 1;
				$res['message'] 	  = "Success";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function balance(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$result2 = $this->login_model->get_full_details('user_tbl','',array('id' => $uid));
			if(count($result2) > 0){
				$res2=array();
				$res2['id'] = $result2->id;
				$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
				$res2['coins_earned'] = $result2->coins_earned;
				$res2['coins_spent'] = $result2->coins_spent;
				$res2['coins_withdrawn'] = $result2->coins_withdrawn;
				//$res2['coins_exist'] = ($result2->coins_earned - ($result2->coins_spent + $result2->coins_withdrawn));
				$res2['coins_exist'] = $totalAmount;
				$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
				$res2['no_of_fan'] = (string)$this->stream_model->fanfunc($result2->id);
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function viewProfile(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$result2 = $this->login_model->get_full_details('user_tbl','',array('id' => $uid));
			if(count($result2) > 0){
				$res2=array();
				$res2['id'] = $result2->id;
				$res2['user_code'] = ($result2->user_code!='')?$result2->user_code:'';
				$res2['first_name'] = $result2->first_name;
				$res2['last_name'] = $result2->last_name;
				$res2['gender'] = $result2->gender;
				$originalDate = $result2->dob;
				$res2['dob'] = date("d-m-Y", strtotime($originalDate));
				$res2['dob1'] = date("Y-m-d", strtotime($originalDate));
				$ag = strlen((string)$this->ageCal($result2->dob));
				$res2['age'] =  ($ag>2)?0:$this->ageCal($result2->dob);
				$res2['mob_no'] = $result2->mob_no;
				$res2['coins_earned'] = $result2->coins_earned;
				$res2['coins_spent'] = $result2->coins_spent;
				$res2['coins_withdrawn'] = $result2->coins_withdrawn;
				$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
				$res2['coins_exist'] = $totalAmount;
				$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
				$res2['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
				$res2['cover_image'] = ($result2->cover_image!='')?$result2->cover_image:'';
				$res2['following'] = $this->login_model->followingfunc('follower_tbl',$uid);
				$res2['follower'] = $this->login_model->followerfunc('follower_tbl',$uid);
				$res2['fans'] = $this->stream_model->fanfunc($uid);
				$res2['tot_viewer'] = $this->login_model->total_viewr($uid);
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function windowLogin2(){
		$res=array();
		$res['res'] = '';
		$id = $this->input->post('id');
		$rand_no = $this->input->post('rand_no');
		$flag = $this->frntlogin_model->qr_chk($rand_no,$id);
		if($flag==1){
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "No Record Found";
		}
		echo json_encode($res);
	}

	public function windowLogin(){
		$accessToken = $this->login_model->tokenAccess();
                //echo '<pre>'; print_r($accessToken); exit;
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$rand_no = $this->input->post('rand_no');
			$flag = $this->frntlogin_model->qr_chk($rand_no,$uid);
                        //echo $flag; exit;
			if($flag==1){
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else {
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}



	public function fileUpload(){
		$res=array();
		//$res['res'] = '';
		$config['upload_path'] = './uploads/user/';
		$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
		$file_name = $_FILES['img']['name'];
		$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
		$config['file_name'] = time().$file_name;
		$this->load->library('upload');
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('img')){
			$error = array('error' => $this->upload->display_errors());
		}else{
			$imgdata = array('upload_data' => $this->upload->data());
			$fnm = $this->config->item('base_url').'uploads/user/'.$imgdata['upload_data']['file_name'];
		}
		$res['file_path'] = $fnm;
		$res['fname'] = $this->input->post('fname');
		echo json_encode($res);
	}



	public function streamViewer(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = GLOBAL_LIMIT;
			$limit_start = $this->input->post('page');
			$stream_id = $this->input->post('stream_id');
			$result = $this->login_model->get_stream_viewer($uid,$stream_id,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res3[$p]['id'] = $val['id'];
					$res3[$p]['first_name'] = $val['first_name'];
					$res3[$p]['last_name'] = $val['last_name'];
					$res3[$p]['mob_no'] = ($val['mob_no']!='')?$val['mob_no']:'';
					$res3[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:$val['profile_image2'];
					$res3[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res3[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res3[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res3[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamViewerParticipant(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = GLOBAL_LIMIT;
			$limit_start = $this->input->post('page');
			$stream_id = $this->input->post('stream_id');
			$result = $this->login_model->get_stream_viewer_participant($uid,$stream_id,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res3[$p]['id'] = $val['id'];
					$res3[$p]['first_name'] = $val['first_name'];
					$res3[$p]['last_name'] = $val['last_name'];
					$res3[$p]['mob_no'] = ($val['mob_no']!='')?$val['mob_no']:'';
					$res3[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:'';
					$res3[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res3[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res3[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res3[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$p++;
				}
				$res['res'] = $res3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 0;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


	public function addChatMsg(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$from = $accessToken->user_id;
			//$tokbox_session_id = $this->input->post('tokbox_session_id');
			//$to = $this->login_model->chatCreateUserId($tokbox_session_id);
			$stream_id = $this->input->post('stream_id');
			$to = $this->login_model->chatCreateUserId($stream_id);
			$message = $this->input->post('message');
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time = $date->format('Y-m-d').' '.$date->format('H:i:s'); //2017-07-29 15:33:32
			//$result = $this->login_model->add_chat_message($tokbox_session_id,$from,$to,$message,$time);
			$result = $this->login_model->add_chat_message($stream_id,$from,$to,$message,$time);
			$res['res'] = $result;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function addChatGift(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$user_id = $from = $accessToken->user_id;
                        //echo $user_id; die();
			$stream_id = $this->input->post('stream_id');
			$to = $this->login_model->chatCreateUserId($stream_id);
			//$to = $this->input->post('to');

			$gift = $this->input->post('gift');
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time = $date->format('Y-m-d').' '.$date->format('H:i:s'); //2017-07-29 15:33:32


			//$total_amt = $this->login_model->gift_amt('gift_tbl',$gift);
			$exp = explode(',',$gift);
			$total_amt = 0;
			if($exp[0]!='' && count($exp)>0){
				$k=0;
				foreach ($exp as $val) {
					$total_amt += $this->login_model->gift_amt('gift_tbl',$val);
					$giftDtl = $this->login_model->gift_dtl('gift_tbl',$val);
					$res4[$k]['id'] = $giftDtl[0]['id'];
					$res4[$k]['title'] = $giftDtl[0]['title'];
					$res4[$k]['gift_img'] = ($giftDtl[0]['gift_img']!='')?$giftDtl[0]['gift_img']:'';
					$k++;
				}
			}
			//echo $total_amt; die();
			$result2 = $this->login_model->chk_balance('user_tbl',$user_id,$total_amt);
                        //echo '<pre>'; print_r($result2); die();
			if($result2 == 1){
				$this->login_model->add_chat_gift($stream_id,$from,$to,$gift,$time);
				$this->login_model->add_balance('user_tbl',$user_id,$to,$total_amt);
				$res['deduction'] = $total_amt;
                                $res['balance'] = $this->login_model->coin_remaining_balance('user_tbl',$user_id,$total_amt);
				$res['res'] = $res4;
				//$this->login_model->updatePoint($to,3);   //receive gift
				//$this->login_model->updatePoint($user_id,4);	//sends gift
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Money";
			}



			//$this->login_model->updatePoint($to,3);   //receive gift
			//$this->login_model->updatePoint($from,4);	//sends gift
			//$res['res'] = $result;
			//$res['errorcode']     = 0;
			//$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function chatHistory(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';

		if(count($accessToken) > 0){
			//$from = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			//$to = $this->input->post('to');
			$time = $this->input->post('time');

			$result = $this->login_model->chat_history($stream_id,$time);
			//print_r($result);
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time2 = $date->format('Y-m-d').' '.$date->format('H:i:s');
			$res['res'] = $result;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
			$res['time'] 	  	  = $time2;
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamingLike(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$stream_id = $this->input->post('stream_id');
			$time = $this->input->post('time');
			$result = $this->login_model->stream_like($stream_id,$time);
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time2 = $date->format('Y-m-d').' '.$date->format('H:i:s');
			$res['flag'] = ($result>0)?1:0;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
			$res['time'] 	  	  = $time2;
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function uploadFeedback(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//$uid = $accessToken->user_id;
			$feedback_id = $this->input->post('feedback_id');
			$err_flg = 1;
			$file_name = $_FILES['fdbk_image1']['name'];
			//echo $file_name;die();
			if(isset($file_name) && $file_name!=''){
				$config['upload_path'] = './uploads/feedback/';
				$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				//$config['max_size']  = '650KB';
				$file_name = preg_replace(FILENAME_PATTERN,'_',$file_name);
				$config['file_name'] = time().$file_name;
				$this->load->library('upload');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('fdbk_image1')){
					//$error = array('error' => $this->upload->display_errors());
					$err_flg = 0;
				}else{
					$imgdata = array('upload_data' => $this->upload->data());
					$data['fdbk_image1'] = $imgdata['upload_data']['file_name'];
				}
			}

			$file_name2 = $_FILES['fdbk_image2']['name'];
			if(isset($file_name2) && $file_name2!=''){
				$config2['upload_path'] = './uploads/feedback/';
				$config2['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				//$config2['max_size']  = '650KB';
				$file_name2 = preg_replace(FILENAME_PATTERN,'_',$file_name2);
				$config2['file_name'] = time().$file_name2;
				$this->load->library('upload');
				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('fdbk_image2')){
					$err_flg = 0;
				}else{
					$imgdata2 = array('upload_data' => $this->upload->data());
					$data['fdbk_image2'] = $imgdata2['upload_data']['file_name'];
				}
			}

			$file_name3 = $_FILES['fdbk_image3']['name'];
			if(isset($file_name3) && $file_name3!=''){
				$config3['upload_path'] = './uploads/feedback/';
				$config3['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
				//$config3['max_size']  = '650KB';
				$file_name3 = $_FILES['fdbk_image3']['name'];
				$file_name3 = preg_replace(FILENAME_PATTERN,'_',$file_name3);
				$config3['file_name'] = time().$file_name3;
				$this->load->library('upload');
				$this->upload->initialize($config3);
				if (!$this->upload->do_upload('fdbk_image3')){
					$err_flg = 0;
				}else{
					$imgdata3 = array('upload_data' => $this->upload->data());
					$data['fdbk_image3'] = $imgdata3['upload_data']['file_name'];
				}
			}
			if($err_flg!=0){
				$this->db->where('id',$feedback_id);
				$this->db->update('feedback_tbl',$data);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Feedback submited successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Feedback submition failed";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function notificationMsg(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = 10;
			$limit_start = $this->input->post('page');
			$res4 = $this->login_model->idInfo($uid);
			//print_r($res4);
			$result = $this->login_model->msg_notify($res4[0]['point_updated'],$limit,$limit_start);
			if(count($result)>0){
				$res['res'] = $result;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}



	public function pushMsgIOS(){
		//set_time_limit(0);
		$message = 'Hi Sanat.';
		$deviceToken = '8D90BF7F32E9C6D293DE70A2F1C8ADFCF9EFAEF6D46899B69D3FDFB86E69C0CB';
		$passphrase = '';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', '/home/demoprojcu/public_html/VisuLive/pushcertVisu.pem');
		//stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		$ipn_server = "ssl://gateway.sandbox.push.apple.com:2195";
		$fp = stream_socket_client($ipn_server,$err,$errstr,60,STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,$ctx);
		if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);
		echo 'Connected to APNS' . PHP_EOL;
		// Create the payload body
		$tPayload = 'APNS Message Handled by LiveCode';
		$sid = 4;
		$body['aps'] = array(
		    //'badge' => +1,
		    'alert' => $message,
		    'sound' => 'default',
			'streamId' => $sid
		);

		//$body['streamId'] = $sid;
		//$body['payload'] = $sid;
		$payload = json_encode($body);
		//$id = rand(9,999);
		//$time = time()+3600;
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		//$msg = pack('CNNnana',1,$id,$time,32,pack('H*',$device_token),strlen($payload),$payload);
		//$msg = chr(0) . chr(0) . chr(32) . pack('H*', $deviceToken) . chr(0) . chr(strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		//echo "<pre>"; print_r($result);
		/*if (!$result)
		    echo 'Message not delivered' . PHP_EOL;
		else
		    echo 'Message successfully delivered. '.$message. PHP_EOL;*/
		// Close the connection to the server
		socket_close($fp);
		fclose($fp);
	}


	public function androidPush($token, $msg, $res2){
		set_time_limit(0);
			$device_token = $token;
			//define( 'API_ACCESS_KEY', 'AAAAJrNrlVM:APA91bH5b_dZ8GBUyTLrzs5NHkqv3qxYUO_jLiRCttm-cZn5KaKkA0I4nWakKpt05JYIFNLzmHApSq3IyTNTtZwqvjyCLQmmzRDPtnD5Vzcgx-cnSKeh3K1uYny-Bp5H8uJxaGoZ6RvG' );
                        define( 'API_ACCESS_KEY', 'AAAAJrNrlVM:APA91bH5b_dZ8GBUyTLrzs5NHkqv3qxYUO_jLiRCttm-cZn5KaKkA0I4nWakKpt05JYIFNLzmHApSq3IyTNTtZwqvjyCLQmmzRDPtnD5Vzcgx-cnSKeh3K1uYny-Bp5H8uJxaGoZ6RvG' );
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
		//	exit();
	}



	public function iosPush($token, $msg, $res2){
		$message = $msg;
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
			'info' => $res2
		);
		//$body['streamId'] = $sid;
		$payload = json_encode($body);
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		$result = fwrite($fp, $msg, strlen($msg));
		@socket_close($fp);
		fclose($fp);
	}



 	public function pushMsg(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$sid = $this->input->post('stream_id');
			//$uid,$sid
			$result2 = $this->login_model->userStreamInfo('user_tbl',array('id' =>$uid));
                        //echo '<pre>'; print_r($result2); die();
                        
			$res2=array();
			$res2['user_details']['id'] = $result2->id;
			$res2['user_details']['first_name'] = $result2->first_name;
			$res2['user_details']['last_name'] = $result2->last_name;
			$res2['user_details']['mob_no'] = $result2->mob_no;
			$res2['user_details']['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:$result2->profile_image2;
			$res2['user_details']['cover_image'] = '';
			$res2['user_details']['is_fan'] = '';
			$totalAmount = $result2->coins_earned  - ($result2->coins_spent + $result2->coins_withdrawn);
			$res2['user_details']['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
			
                        $result3 = $this->login_model->userStreamInfo('stream_tbl',array('id' =>$sid));
			//echo '<pre>'; print_r($result3); die();
                        
                        $res2['id'] = $sid;
			//$res2['tokbox_session_id'] = $result3->tokbox_session_id;
			//$res2['stream_url'] = $result3->stream_url;
			//$res2['broadcast_id'] = $result3->broadcast_id;
			$res2['tokbox_session_id'] = '';
			$res2['stream_url'] = '';
			$res2['broadcast_id'] = '';
			$res2['view_cnt'] = $result3->view_cnt;
			$res2['location'] = isset($result3->location)?$result3->location:'';
			$res2['created'] = date("d/m/Y h:ia",strtotime($result3->created));

			$first_name = $result2->first_name;
			$msg = $first_name.' is going to be live.';
                        //echo $this->iosPush('8A5BAFD353721CF4F90A8CE2919C2C9BCEA93C9A6DB8E620DA0EA6CD6E2700DE', $msg, $res2);
                        //echo $this->androidPush('fnG839GuxW0:APA91bGI8c9nk-qlneW6iPL1IyWUegIAdln6FLwqOupNY_bYTN6AsUJhonckh5VFY2grIliyQQ6d9BK8NT7BezeCrDISQq13pfnrxsksTsGC5-2fEpRcF0_QO3BsM459aXzRtABWRsDv', $msg, $res2);
                       
                        $result = $this->login_model->streamFollerDevice($uid);
                        //echo count($result); //die();
                        // echo '<pre>'; print_r($result); //die();
                        
			if(count($result) > 0){
				foreach($result as $val){
                                    //echo $val['id'];
					if($val['device_type']=='ios'){
                                            //echo "ios";
                                            $this->iosPush($val['device_token'], $msg, $res2);
					}else if($val['device_type']=='android'){
                                            //echo "android";
                                            $this->androidPush($val['device_token'], $msg, $res2);
					}
				}
			}
			$res['res2']     = $res2;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function appOpen(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			//$this->login_model->updatePoint($uid,1);
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function like(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$send_id = $this->input->post('send_id');
			$stream_id = $this->input->post('stream_id');
			$data['user_id'] = $uid;
			$data['send_id'] = $send_id;
			$data['stream_id'] = $stream_id;
			$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
			$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
			$data['created'] = $time;
			$chk = $this->login_model->chk_like($uid,$send_id,$stream_id);
			if($chk<1){
				$this->db->insert('like_tbl', $data);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Like Successfully";
			}else{
				$this->login_model->update_like($uid,$send_id,$stream_id, $time);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Already Liked";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamParticipateClose(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$chk = $this->login_model->chk_participate_stream($stream_id,$uid);
			if($chk>0){
				$this->login_model->update_status_participants_stream_rec($stream_id, $uid, 0);//Changed by Rakesh on 16.01.2018
				$res['errorcode']     = 0;
				$res['message'] 	  = "Delete Successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No One";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function appTest(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$id = 2;
			$this->login_model->count_stream_like($id);
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function forgetPassword(){
		$mob_no = $this->input->post('mob_no');
                $country_code = $this->login_model->fetch_country_code($mob_no);
                //echo '<pre>'; print_r($country_code); die();
                $country_code_val = $country_code->country_code;
                //echo $country_code_val; die();
                
		$res=array();
		$res['res'] = '';
		if(!isset($mob_no)){
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}
		$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^[0-9]{10,12}$/]');
		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Invalid mobile number";
		}else{
			$result2=$this->login_model->get_user_pass($mob_no);
			if(count($result2) > 0){
                                //echo "if blovked"; die();
                                $activation_code = $this->random_digit(6);
                                
                                $res2=array();
				$res2['mob_no'] = $result2->mob_no;
				$res2['password'] = $this->encrypt_decrypt('decrypt',$result2->password);
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
                                $res['activation_code'] = $activation_code;
                                
                                
                                $this->login_model->forgotpassword_activation_code_update($mob_no,$activation_code);
				$check = $this->login_model->forgetpassword_varify_check_twilio($mob_no);
                                //echo '<pre>'; print_r($check); die();
                                //$this->load->library('twilio');
                                $from = '+14848934449';
                                $to = $country_code_val.$mob_no;
                                //$to = '+919830112831';
                                $twilio_activation_code = $check->activation_code;
                                
                                $message = 'VISU LIVE Verification : '.$twilio_activation_code;
                                //$response = $this->twilio->sms($from, $to, $message);
								
								$sid = "ACa01d021579c1c687bf362ea51b9917fe"; // Your Account SID from www.twilio.com/console
								$token = "eb121d1336db31a6f1781db0d761efa7";
								$client = new Client($sid, $token);			
								$message = $client->messages->create(
								  $to, // Text this number
								  array(
									'from' => $from, // From a valid Twilio number
									'body' => $message
								  )
								);
								
                                //if($response->IsError){
                                        //echo 'Sms Has been Not sent'; 
                                //}else{
                                        //echo 'Sms Has been sent'; die();
                                //}
                                
                                
			}else{
                                //echo "else blocked"; die();
				$res['errorcode']     = 1;
				$res['message'] 	  = "This mobile number is not registered.";
			}
		}
		echo json_encode($res);
	}
        
        
        public function forgetPasswordVarify(){
		$data['mob_no'] = $this->input->post('mob_no');
		$data['activation_code'] = trim($this->input->post('activation_code'));
		$res=array();
		$res['res'] = '';
		$result3 = $this->login_model->forgetpassword_varify_check($data['mob_no'],$data['activation_code']);
		//echo '<pre>'; print_r($result3); die();
                //echo count($result3); die();
                if(count($result3)>0){
                        $res['res'] = $result3;
                        $res['errorcode']     = 0;
                        $res['message'] 	  = "Successfully Active";
                }else{
                        $res['errorcode']     = 1;
                        $res['message'] 	  = "Invalid Code";
                }
		
		echo json_encode($res);
	}

        public function forgetPasswordUpdate(){
		$mob_no = $this->input->post('mob_no');
			$newpassword = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
			//echo $newpassword; die();
		$res=array();
		$res['res'] = '';
		if(!isset($mob_no)){
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}
		$this->form_validation->set_rules('mob_no','Mobile Number','required|regex_match[/^[0-9]{10,12}$/]');
		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Invalid mobile number";
		}else{
			$result2=$this->login_model->get_user_pass($mob_no);
			if(count($result2) > 0){
                                
                                $this->login_model->forgotpassword_new_update($mob_no,$newpassword);
				$res2=array();
                                
				$res2['mob_no'] = $result2->mob_no;
				//$res2['password'] = $this->encrypt_decrypt('decrypt',$result2->password);
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Password changed successfully.";
                               
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}
		echo json_encode($res);
	}
        
	public function callCheck(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['cnt'] = 0;
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$cnt=$this->login_model->count_stream_publisher($stream_id);
			if($cnt>=2){
				$res['errorcode']     = 1;
				$res['message'] 	  = "User Busy";
			}else{
				$this->login_model->update_participants_status($stream_id,$uid);
				if(!$this->login_model->coin_duduction($uid)){
                                    $res['errorcode']     = 0;
                                    $res['message'] 	  = "Success";
                                }else{
                                    $res['errorcode']     = 1;
                                    $res['message'] 	  = "Insufficient balance";
                                }
				
			}
			$res['cnt']     = $cnt;
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function subscriberTopublisher(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$this->login_model->subscriber_to_publisher($stream_id,$uid);
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function streamFanCnt(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['cnt'] = 0;
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$stream_id = $this->input->post('stream_id');
			$cnt=$this->login_model->count_stream_fan($stream_id);
			if($cnt>0){
				$res['cnt'] = $cnt;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function coinDeduction(){
		$accessToken = $this->login_model->tokenAccess();
                //echo '<pre>'; print_r($accessToken); die();
		$res=array();
		//$res['cnt'] = 0;
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$user_id = $this->input->post('user_id');
			$rest=$this->login_model->coin_duduction($uid);
			if($rest==0){
				$res['errorcode']     = 0;
                                $res['balance'] = $this->login_model->coin_remaining_balance('user_tbl',$uid);
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Empty";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function twilioNumber(){
		$res=array();
		//$this->load->library('twilio');
		$mob_no = $this->input->post('mob_no');
		$country_code = $this->input->post('country_code');
		//$from = '+14158799786';
                $from = '+14848934449';
                //$to = '+919830112831';
                
		$to = $country_code.$mob_no;
                //echo $to; die();
		$result3 = $this->login_model->varify_twilio_no($country_code,$mob_no);
		$cnt = count($result3);
                //echo $cnt; die();
		if($cnt>0){
                        //echo "gggggg"; die();
			$res['errorcode']     = 1;
			$res['message'] 	  = "Mobile No. already registered.";
		}else{
                        //echo "OK"; die();
			$activation_code = $this->random_digit(6);
			$data['mob_no'] = $mob_no;
			$data['country_code'] = $country_code;
			$data['activation_code'] = $activation_code;
			$this->db->insert('twilio_tbl', $data);
			$lastInsert = $this->db->insert_id();
			$message = 'VISU LIVE Verification : '.$activation_code;
			/*$response = $this->twilio->sms($from, $to, $message);
			if($response->IsError){
                                //echo 'Sms Has been Not sent'; die();
			 	$res['errorcode']     = 1;
				$res['message'] 	  = "Error";
			 }else{
                                //echo 'Sms Has been sent'; die();
				$res['errorcode']     = 0;
			 	$res['message'] 	  = "Success";
			 }*/
			 
			$sid = "ACa01d021579c1c687bf362ea51b9917fe"; // Your Account SID from www.twilio.com/console
			$token = "eb121d1336db31a6f1781db0d761efa7";
			$client = new Client($sid, $token);
			
			$message = $client->messages->create(
			  $to, // Text this number
			  array(
				'from' => $from, // From a valid Twilio number
				'body' => $message
			  )
			);
			
			if($message->sid){
				$res['errorcode']     = 0;
			 	$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Error";
			}
                         
			$response = 1;
			if($response==1){
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
				$res['activation_code'] = $activation_code;
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Error";
			}
		}
		echo json_encode($res);
	}

	public function twilioVarify(){
		$data['mob_no'] = $this->input->post('mob_no');
		$data['country_code'] = $this->input->post('country_code');
		$data['activation_code'] = trim($this->input->post('activation_code'));
		$res=array();
		$res['res'] = '';
		$result3 = $this->login_model->varify_check($data['country_code'],$data['mob_no'],$data['activation_code']);
		//$result2 = $this->login_model->duplicate($data['mob_no']);
		if($this->login_model->isValideMobile($data) > 0){
			$res['errorcode']   = 1;
			$res['message']     = "The mobile number you entered is already registered.";
		}else{
			if(count($result3)>0){
				$res['res'] = $result3;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Successfully Active";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Invalid Code";
			}
		}
		echo json_encode($res);
	}

	public function bannerList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = 30;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->banner_list($limit,$limit_start);
			if(count($result)>0){
				$res2=array();
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['banner_url'] = $val['banner_url'];
					$res2[$p]['banner_img'] = ($val['banner_img']!='')?$val['banner_img']:'';
					$p++;
				}
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function clearTable(){
		$this->db->truncate('stream_tbl');
		$this->db->truncate('stream_participants_tbl');
		$this->db->truncate('purchase_tbl');
		$this->db->truncate('myincome_tbl');
		$this->db->truncate('follower_tbl');
		$this->db->truncate('fan_tbl');
		$this->db->truncate('chat_tbl');
		$this->db->truncate('like_tbl');
		$this->db->truncate('notification_tbl');
		$this->db->truncate('feedback_tbl');

		//$this->db->truncate('user_tbl');
		//$this->db->truncate('device_tbl');
		//$this->db->truncate('twilio_tbl');
		//$this->db->truncate('qr_tbl');
	}

	public function viewOtherProfile(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$user_id = $this->input->post('user_id');
			$result = $this->login_model->getUserName($user_id);
			//print_r($result);
			$uid = $accessToken->user_id;
			$res2 = array();
			if(count($result) > 0){
				$res2=array();
				$res2['id'] =  $result->id;
				$res2['user_code'] = ($result->user_code!='')?$result->user_code:'';
				$res2['first_name'] =  $result->first_name;
				$res2['last_name'] =  $result->last_name;
				$res2['mob_no'] =  $result->mob_no;
				$res2['gender'] =  $result->gender;
				$res2['dob'] =  $result->dob;
				$ag = strlen((string)$this->ageCal($result->dob));
				$res2['age'] = ($ag>2)?0:$this->ageCal($result->dob);
				$res2['profile_image'] = ($result->profile_image!='')?$result->profile_image:'';
				$res2['cover_image'] = ($result->cover_image!='')?$result->cover_image:'';
				$totalAmount = $result->coins_earned  - ($result->coins_spent + $result->coins_withdrawn);
				$res2['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
				$res2['no_of_fan'] = (string)$this->stream_model->fanfunc($result->id);
				$res2['is_follow'] = $this->login_model->chk_follower($uid,$result->id);
				$res2['following'] = $this->login_model->followingfunc('follower_tbl',$result->id);
				$res2['follower'] = $this->login_model->followerfunc('follower_tbl',$result->id);
				$res2['location'] = ($result->location!='')?$result->location:'';
				//$res2[0]['no_of_fan'] = (string)$this->stream_model->fanfunc($result->id);
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function userFanList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$user_id = $this->input->post('user_id');
			//$limit = GLOBAL_LIMIT;
			$limit = 100;
			$limit_start = $this->input->post('page');
			$uid = $accessToken->user_id;
			//$result = $this->login_model->get_fan_res($uid,$user_id,$limit,$limit_start);
			$result = $this->login_model->fan_list($user_id,$limit,$limit_start);
			//print_r($result);
			//die();
			if(count($result) > 0){
				$p=0;
				$res2=array();
				foreach ($result as $key => $val) {
					$result2 = $this->login_model->fanInfo('user_tbl',array('id' =>$val['fan_id']));
					$res2[$p]['id'] = $result2->id;
					$res2[$p]['first_name'] = $result2->first_name;
					$res2[$p]['last_name'] = $result2->last_name;
					$res2[$p]['mob_no'] = $result2->mob_no;
					$res2[$p]['gender'] = $result2->gender;
					$res2[$p]['dob'] = $result2->dob;
					$ag = strlen((string)$this->ageCal($result2->dob));
					$res2[$p]['age'] = ($ag>2)?0:$this->ageCal($result2->dob);
					//$res2[$p]['coins_earned'] = $result2->coins_earned;
					$res2[$p]['profile_image'] = ($result2->profile_image!='')?$result2->profile_image:'';
					$res2[$p]['cover_image'] = ($result2->cover_image!='')?$result2->cover_image:'';
					$res2[$p]['location'] = ($result2->location!='')?$result2->location:'';
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function moneyList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$result = $this->login_model->get_currency();
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['coins'] = $val['coins'];
					$res2[$p]['dollar'] = $val['dollar'];
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function followingList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = 30;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->following_rec($uid,$limit,$limit_start);
			//print_r($result);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['user_code'] = ($val['user_code']!='')?$val['user_code']:'';
					$res2[$p]['first_name'] = $val['first_name'];
					$res2[$p]['last_name'] = $val['last_name'];
					$res2[$p]['mob_no'] = $val['mob_no'];
					$res2[$p]['gender'] = $val['gender'];
					$res2[$p]['dob'] = $val['dob'];
					$ag = strlen((string)$this->ageCal($val['dob']));
					$res2[$p]['age'] = ($ag>2)?0:$this->ageCal($val['dob']);
					$res2[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:$val['profile_image2'];
					$res2[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res2[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res2[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$res2[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res2[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res2[$p]['no_of_fan'] = (string)$this->stream_model->fanfunc($val['id']);
					$totalAmount = $val['coins_earned']  - ($val['coins_spent'] + $val['coins_withdrawn']);
					$res2[$p]['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2[$p]['location'] = ($val['location']!='')?$val['location']:'';
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function followerList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = 30;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->follower_rec($uid,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['user_code'] = ($val['user_code']!='')?$val['user_code']:'';
					$res2[$p]['first_name'] = $val['first_name'];
					$res2[$p]['last_name'] = $val['last_name'];
					$res2[$p]['mob_no'] = $val['mob_no'];
					$res2[$p]['gender'] = $val['gender'];
					$res2[$p]['dob'] = $val['dob'];
					$ag = strlen((string)$this->ageCal($val['dob']));
					$res2[$p]['age'] = ($ag>2)?0:$this->ageCal($val['dob']);
					$res2[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:$val['profile_image2'];
					$res2[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res2[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res2[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$res2[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res2[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res2[$p]['no_of_fan'] = (string)$this->stream_model->fanfunc($val['id']);
					$totalAmount = $val['coins_earned']  - ($val['coins_spent'] + $val['coins_withdrawn']);
					$res2[$p]['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2[$p]['location'] = ($val['location']!='')?$val['location']:'';
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function userList(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$limit = 30;
			$limit_start = $this->input->post('page');
			$search_val = strtolower(trim($this->input->post('search_val')));
			$result = $this->login_model->user_rec($uid,$search_val,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach($result as $val){
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['user_code'] = ($val['user_code']!='')?$val['user_code']:'';
					$res2[$p]['first_name'] = $val['first_name'];
					$res2[$p]['last_name'] = $val['last_name'];
					$res2[$p]['mob_no'] = $val['mob_no'];
					$res2[$p]['gender'] = $val['gender'];
					$res2[$p]['dob'] = $val['dob'];
					$ag = strlen((string)$this->ageCal($val['dob']));
					$res2[$p]['age'] = ($ag>2)?0:$this->ageCal($val['dob']);
					$res2[$p]['profile_image'] = ($val['profile_image']!='')?$val['profile_image']:$val['profile_image2'];
					$res2[$p]['cover_image'] = ($val['cover_image']!='')?$val['cover_image']:'';
					$res2[$p]['created'] = date("d/m/Y h:ia",strtotime($val['created']));
					$res2[$p]['is_follow'] = $this->login_model->chk_follower($uid,$val['id']);
					$res2[$p]['following'] = $this->login_model->followingfunc('follower_tbl',$val['id']);
					$res2[$p]['follower'] = $this->login_model->followerfunc('follower_tbl',$val['id']);
					$res2[$p]['no_of_fan'] = (string)$this->stream_model->fanfunc($val['id']);
					$totalAmount = $val['coins_earned']  - ($val['coins_spent'] + $val['coins_withdrawn']);
					$res2[$p]['level'] = $this->login_model->levelfunc('level_tbl',$totalAmount);
					$res2[$p]['location'] = ($val['location']!='')?$val['location']:'';
					$p++;
				}
				$res['res']     = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function takeLocation(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			$data['location'] = $this->input->post('location');
			$this->db->where('id', $uid);
			$this->db->update('user_tbl',$data);
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}

	public function countryList(){
        //echo "hello"; die();
		$res=array();
		$res['res'] = '';
		$limit = 300;
		$limit_start = $this->input->post('page');
		$result = $this->login_model->get_country_list($limit,$limit_start);
		if(count($result) > 0){
			//print_r($result);
			$p=0;
			foreach($result as $val){
				$res2[$p]['id'] = $val['id'];
				$res2[$p]['country_name'] = $val['country_name'];
				$res2[$p]['country_code'] = $val['country_code'];
				$res2[$p]['short_code'] = $val['short_code'];
                                $res2[$p]['popular'] = $val['popular'];
				$p++;
			}
			$res['res']     = $res2;
			$res['errorcode']     = 0;
			$res['message'] 	  = "Success";
		}else{
			$res['errorcode']     = 1;
			$res['message'] 	  = "No Record Found";
		}
		echo json_encode($res);
	}

	public function resetPassword(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			$uid = $accessToken->user_id;
			//$data['password'] = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
			$old_password = $this->encrypt_decrypt('encrypt',$this->input->post('old_password'));
			$data['password'] = $this->encrypt_decrypt('encrypt',$this->input->post('new_password'));
			$info=$this->login_model->idInfo($uid);
			$old_db_password = $info[0]['password'];
			if($old_password==$old_db_password){
				$this->db->where('id', $uid);
				$this->db->update('user_tbl',$data);
				$res['errorcode']     = 0;
				$res['message'] 	  = "Password has been reset successfully";
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "Old password does not match";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}
	public function moneyWithdrawn(){
		$accessToken = $this->login_model->tokenAccess();
		$res=array();
		$res['res'] = '';
		if(count($accessToken) > 0){
			//$limit = GLOBAL_LIMIT;
			$limit = 10;
			$uid = $accessToken->user_id;
			$limit_start = $this->input->post('page');
			$result = $this->login_model->trans_withdrawn($uid,$limit,$limit_start);
			if(count($result) > 0){
				$p=0;
				foreach ($result as $val) {
					$res2[$p]['id'] = $val['id'];
					$res2[$p]['first_name'] = $val['first_name'];
					$res2[$p]['last_name'] = $val['last_name'];
					$res2[$p]['mycoin'] = $val['mycoin'];
					$res2[$p]['created'] = date("d.m.Y",strtotime($val['created']));
					$res2[$p]['status'] = ($val['status']==0)?'Requested':'Released';
					$p++;
				}
				$res['res'] = $res2;
				$res['errorcode']     = 0;
				$res['message'] 	  = "Success";
			}else{
				$res['res'] = "No Record Found.";
				$res['errorcode']     = 1;
				$res['message'] 	  = "Success";
			}
		}else{
			$res['errorcode']     = 3;
			$res['message'] 	  = "Your session has been expired. Please login again.";
		}
		echo json_encode($res);
	}


     public function hello(){
		$res=array();
		$res['res'] = 'Hello';
		
		echo json_encode($res);
	}



}
