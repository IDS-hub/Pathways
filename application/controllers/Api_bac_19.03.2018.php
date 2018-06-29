<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model(array('login_model','frntlogin_model'));
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

	

	public function WebRequestSignUp(){
		$res=array();
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$data['password'] 		= $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		//$data['confirmpassword'] 	= $this->encrypt_decrypt('encrypt',$this->input->post('confirmpassword'));
		$data['email'] 			= $this->input->post('email');

		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');
		//$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required');

		if($this->form_validation->run() === FALSE){
			//echo "Hello"; die();
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			//echo "OK"; die();
			if($this->login_model->isValideEmail($data) > 0){
				//echo "Email check"; die();
				$res['res'] = '';
				$res['errorcode']   = 1;
				//$res['message']     = "The email id you entered is already registered.";
				$rest['message']     = "The email id you entered is already registered.";
                                $rest['success'] = false;
			}
			else{
				//echo "email check skip"; die();
				$dvc_data['access_token']=$this->random_string(16);
				$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
				$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
				$data['created'] = $time;
				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] 	= $lastInsert;
				$this->db->insert('device_tbl', $dvc_data);
				$res['errorcode']  = 0;
				$res['message'] = "Thank you for registration";
				
				
			$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
			//$rest['id'] = $result->id;
			$rest['first_name'] = $result->first_name;
			$rest['last_name'] = $result->last_name;
			$rest['email'] = $result->email;
			
			$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id),array(),array(),0,0,0);

			if(count($result2) > 0){
				$rest['accessToken'] = $result2->access_token;
				$rest['success'] = true;
			}else{
				$this->db->select('*')->where(array('user_id'=>$result->id));
				$this->db->delete('device_tbl');
				$access_token = $this->random_string(16);
				$pdata = array('user_id' => $result->id,'access_token' => $access_token);
				$str = $this->db->insert('device_tbl', $pdata);
				$rest['accessToken'] = $access_token;
				$rest['success'] = true;
			}
			$res['res'] = $rest;
				
		   }
			
		}
		//echo json_encode($res);
		echo json_encode($rest);
	}

	public function ageCal($Bday){
		$today = new DateTime();
		$diff = $today->diff(new DateTime($Bday));
		return (($diff->y)>0)?$diff->y:0;
	}

	public function WebRequestSignIn(){
		//echo "hhhhhhh"; die();
		$data['email']       = $this->input->post('email');
		$data['password']     = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		
		$res=array();
		$res['res'] = '';
		$this->form_validation->set_rules('email','Email','required');
		$this->form_validation->set_rules('password','Password','required');
		
		if($this->form_validation->run() === FALSE){
			$res['errorcode'] = 1;
			$res['message']  = "Please provide all parameters";
		}else{
			$result = $this->login_model->get_full_details('user_tbl','*',array('email' => $data['email'],'password'=>$data['password']),array(),array(),0,0,0);
			if(count($result) > 0){
				if($result->is_active=='1'){
					$result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id),array(),array(),0,0,0);

//					if(count($result2) > 0){
//                                                echo "11111"; die();
//						$rest['accessToken'] = $result2->access_token;
//						$rest['success'] = true;
//					}else{
						//echo "222222"; die();
						$this->db->select('*')->where(array('user_id'=>$result->id));
						$this->db->delete('device_tbl');
						$access_token = $this->random_string(16);
						//$pdata = array('device_type' => $data['device_type'], 'device_token' => $data['device_token'],'user_id' => $result->id,'access_token' => $access_token);
						$pdata = array('user_id' => $result->id,'access_token' => $access_token);
						$str = $this->db->insert('device_tbl', $pdata);
						$rest['accessToken'] = $access_token;
						$rest['success'] = true;
					//}
					
					$res['res'] = $rest;
					//$res['errorcode']     = 0;
					//$res['message'] 	  = "Success";
				}else {
					$res['errorcode']     = 1;
					$res['message'] 	  = "Status Inactive";
				}
			}else{
				//$res['errorcode']     = 1;
				//$res['message'] 	  = "The email ID or password you entered is incorrect";
				$rest['success'] = false;
			}
		}
		echo json_encode($rest);
	}
	
    public function WebRequestSignOut() {
        $accessToken = $this->input->post('accessToken');
        //echo $accessToken; die();
        $result = $this->login_model->get_full_details('device_tbl','*',array('access_token' => $accessToken),array(),array(),0,0,0);
	//echo $result; die();
       if(count($result) > 0){
            $this->db->select('*')->where(array('access_token'=>$accessToken));
            $this->db->delete('device_tbl');
            $rest['success'] = true;
            
        }else {
            $rest['success'] = false;
        }
        echo json_encode($rest);
    }
    
    public function WebRequestProfileShow() {
        $accessToken = $this->input->post('accessToken');
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); 
        if(count($info) > 0) {
            $result2 = $this->login_model->get_full_details('user_tbl','*',array('id' => $info->user_id),array(),array(),0,0,0);
            //echo '<pre>'; print_r($result2); exit;
            
			$userDignosisAll = $this->login_model->user_dignosis($info->user_id);
			if(count($userDignosisAll)>0){
				$diagnosisIds = [];
				$diagnosisTitles = [];
				foreach($userDignosisAll as $key=>$userDignosis){
					$diagnosisIds[] = $userDignosis['diagnosis_id'];
					$remove = array(0);
					$diagnosisIds = array_diff($diagnosisIds, $remove);
					$string_diagnosisIds = implode(',', $diagnosisIds);
					
					$diagnosisTitles[] = $userDignosis['diagnosis_title'];
					$diagnosisTitles = array_filter($diagnosisTitles, 'strlen');
					$string_diagnosisTitles = implode(',', $diagnosisTitles);
				}
			}
			
            $rest['first_name'] = $result2->first_name;
            $rest['last_name'] = $result2->last_name;
            $rest['email'] = $result2->email;
            $rest['accessToken'] = $accessToken;
            $rest['reset_password_token'] = '';
            $rest['avatarJsonData'] = $result2->avatarData;
			$rest['user_added_diagnosis'] = ($string_diagnosisIds!='')?$string_diagnosisIds:'';
			$rest['user_added_new_diagnosis'] = ($string_diagnosisTitles!=''?$string_diagnosisTitles:'');
            $rest['success'] = true;
        } else {
            $rest['success'] = false;
        }
        echo json_encode($rest);
    }
   
     public function WebRequestProfileUpdate() {
        $accessToken            = $this->input->post('accessToken');
        $data['first_name'] 	= trim($this->input->post('first_name'));
	$data['last_name'] 	= trim($this->input->post('last_name'));
        
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); 
        $userID = $info->user_id;
        //echo $userID; die();
        if(count($info) > 0) {
            $result = $this->login_model->updateProfile('user_tbl',$userID,$data);
            if($result!=0){
                $result2 = $this->login_model->get_full_details('user_tbl','*',array('id' => $userID),array(),array(),0,0,0);
                //echo '<pre>'; print_r($result2); exit;
                $rest['first_name'] = $result2->first_name;
                $rest['last_name'] = $result2->last_name;
                $rest['reset_password_token'] = '';
                $rest['success'] = true;
            } else {
                $rest['success'] = false;
            }
            
        } else {
            $rest['success'] = false;
        }
        echo json_encode($rest);
    }
    
    public function WebRequestPasswordEdit(){
		$email = $this->input->post('email');
                $newpassword = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
                //echo $newpassword; die();
		$res=array();
		//$res['res'] = '';
		/*if(!isset($mob_no)){
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			echo json_encode($res);
			exit;
		}*/
		$this->form_validation->set_rules('email','Email','trim|required');
                $this->form_validation->set_rules('password','Password','trim|required');
		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Please provide all parameters";
		}else{
			$result2=$this->login_model->get_user_pass($email);
                        //echo count($result2); die();
			if(count($result2) > 0){
                                $this->login_model->forgotpassword_new_update($email,$newpassword);
				
				$res['success'] 	  = true;
                               
			}else{
				$res['success'] 	  = false;
			}
		}
		echo json_encode($res);
	}

    public function WebRequestProfileAuthProviders(){
		
		$data['first_name']             = trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
        //$data['email'] 		= trim($this->input->post('email'));
		
		$data['facebook_id'] 		= trim($this->input->post('facebook_id'));
		
		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
        //$this->form_validation->set_rules('email','Email','trim|required');
		$this->form_validation->set_rules('facebook_id','Facebook ID','trim|required');

		if($this->form_validation->run() === FALSE){
			//echo "1"; die();
			$rest['errorcode'] =	1;
			$rest['message']="Please provide all parameters";
		}else{
			//echo "2"; die();
			$token = $this->random_string(16);
			$dvc_data['access_token'] = $token;
			$gid = $data['facebook_id'];
			$result = $this->login_model->get_full_details('user_tbl','*',array('facebook_id' => $data['facebook_id']),array(),array(),0,0,0);
			if(count($result)<1){
				//echo "1"; die();
				$data['is_active'] = '1';
				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] = $lastInsert;
				$dvc_data['access_token'] = $token;
				$this->db->insert('device_tbl', $dvc_data);
				$result3 = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
				
				
                $result4 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $lastInsert),array(),array(),0,0,0);
				$rest['accessToken'] = $result4->access_token;
				
                $rest['success'] = true;
                                
			}else{  
			     //echo "2"; die();
                 $result2 = $this->login_model->get_full_details('device_tbl','*',array('user_id' => $result->id),array(),array(),0,0,0);
				if(count($result2) > 0){
					$rest['accessToken'] = $result2->access_token;
				}else{
					$this->db->select('*')->where(array('user_id'=>$result->id));
					$this->db->delete('device_tbl');
					$access_token = $this->random_string(16);
					$pdata = array('user_id' => $result->id,'access_token' => $access_token);
					$str = $this->db->insert('device_tbl', $pdata);
					$rest['accessToken'] = $access_token;
				}
				
                                $rest['success'] 	  = true;
			}
		}
                echo json_encode($rest);
	}
        
    public function WebRequestPainCauseShow() {
        $accessToken  = $this->input->post('accessToken');
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
                //echo "OK";
                $result = $this->login_model->getSessionInfo('session_tbl');
                //echo '<pre>'; print_r($result); die();
                if(count($result) >0) {
                    $p=0;
                    foreach($result as $val){
                            $res2[$p]['id'] = $val['id'];
                            $res2[$p]['name'] = $val['title'];
                            $res2[$p]['media-duration-sec'] = $val['duration'];
                            $res2[$p]['session-text'] = $val['session_description'];
                            $res2[$p]['position'] = '';
                            $res2[$p]['pain-cause-id'] = '';
                            $p++;
                    }
                    $res['res']     = $res2;
                    //$res['errorcode']     = 0;
                    $res['success'] 	  = true;
                }else{
                        //$res['errorcode']     = 1;
                        $res['success'] 	 = false;
                }
        }
         
        echo json_encode($res);
    }

    public function WebRequestSessionCreate() {
        $accessToken  = $this->input->post('accessToken');
        $data['session_id']= $this->input->post('session_id');
        $data['pain_level'] = $this->input->post('pain_level');
        
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); 
        $userID = $info->user_id;
        $data['user_id'] = $userID;
        
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
            $this->db->insert('user_sessions', $data);
            $res['success'] = true;
        }
        echo json_encode($res);
    }
    
    public function GetAllDiagnosis() {
      
                $result = $this->login_model->getAllDiagnosisInfo('diagnosis_tbl');
                //echo '<pre>'; print_r($result); die();
                if(count($result) >0) {
                    $p=0;
                    foreach($result as $val){
							$res2[$p]['id'] = $val['id'];
                            $res2[$p]['diagnosis'] = $val['title'];
                            $res2[$p]['posx'] = $val['posx'];
                            $res2[$p]['posy'] = $val['posy'];
                            $res2[$p]['posz'] = $val['posz'];
                            $res2[$p]['rotx'] = $val['rotx'];
                            $res2[$p]['roty'] = $val['roty'];
                            $res2[$p]['rotz'] = $val['rotz'];
                            $res2[$p]['fov'] = $val['fov'];
                            $res2[$p]['targetBone'] = $val['targetBone'];
                            $p++;
                    }
                    $res['res']     = $res2;
                    //$res['errorcode']     = 0;
                    $res['success'] 	  = true;
                }else{
                        //$res['errorcode']     = 1;
                        $res['success'] 	 = false;
                }
       
         
        echo json_encode($res);
    }
    
    public function userAddedDiagnosis() {
        $accessToken  = $this->input->post('accessToken');
        $data['diagnosis_request_title']= rtrim($this->input->post('diagnosis_request_title'),',');
        //echo '<pre>'; print_r($data); die();
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); 
        $userID = $info->user_id;
        $data['created_by_user_id'] = $userID;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
            
            $this->db->insert('diagnosis_request_tbl', $data);
            $res['success'] = true;
        }
        echo json_encode($res);
        
    }
    
    public function SaveUserAvatar() {
        $accessToken  = $this->input->post('accessToken');
        $data['avatarData']  = trim($this->input->post('avatarJsonData'));
        
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); 
        $userID = $info->user_id;
       
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
            $result = $this->login_model->updateProfile('user_tbl', $userID, $data);
            if($result!=0){
                $info = $this->login_model->userRec('user_tbl',$userID);
                //$res['avatarJsonData'] = $info->avatarData;
                $res['success'] = true;
            }
            else 
            {
                $res['success'] = false;
            }
            
        }
        echo json_encode($res);
    }
	
	public function AddUserDiagnonis() {
        $accessToken  = $this->input->post('accessToken');        
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
        $userID = $info->user_id;
        
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
            $allDiagnosisIds = explode(',',$this->input->post('user_added_diagnosis'));
			if(count($allDiagnosisIds) > 0){
				if($this->login_model->isUserPresent($userID) > 0){
					$this->db->select('*')->where(array('user_id'=>$userID));
					$this->db->delete('user_diagnosis_tbl');											
				}
				foreach($allDiagnosisIds as $dignosisId){
					$data['user_id'] = $userID;
					$data['diagnosis_id'] = $dignosisId;
					$this->db->insert('user_diagnosis_tbl', $data);
				}
			}
			$allDiagnosisTitles = explode(',',$this->input->post('user_added_new_diagnosis'));
			if(count($allDiagnosisTitles) > 0){
				if($this->login_model->isUserPresent($userID) > 0){
					$this->db->select('*')->where(array('created_by_user_id'=>$userID));
					$this->db->delete('diagnosis_request_tbl');											
				}
				foreach($allDiagnosisTitles as $dignosisTitle){
					//print_r($dignosisTitle);exit;
					$data1['user_id'] = $userID;
					$data1['diagnosis_title'] = $dignosisTitle;
					$this->db->insert('user_diagnosis_tbl', $data1);
					
					$data2['diagnosis_request_title'] = $dignosisTitle;
					$data2['created_by_user_id'] = $userID;
					$this->db->insert('diagnosis_request_tbl', $data2);
				}
			}
			
            
            $res['success'] = true;
        }
        echo json_encode($res);
        
    }
	
	
	public function forgetPassword(){
		$email = $this->input->post('email');
		
		$this->form_validation->set_rules('email','Email','trim|required');
		if($this->form_validation->run() === FALSE){
			//echo "1"; die();
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Invalid email id.";
			$res['success'] = false;
		}else{
			//echo "2"; die();
			$result2=$this->login_model->get_user_pass($email);
			if(count($result2) > 0){
				//echo "if blovked"; die();
				$res['email'] = $result2->email;
				$res['password'] = $this->encrypt_decrypt('decrypt',$result2->password);
				$res['message'] 	  = "Success";
                $res['success'] = true;
                                
			}else{
                //echo "else blocked"; die();
				$res['errorcode']     = 1;
				$res['message'] 	  = "This email id is not registered.";
				$res['success'] = false;
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
		$email = $this->input->post('email');
		$newpassword = $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		//echo $newpassword; die();
		if(!isset($email)){
			$res['res'] = '';
			$res['errorcode']     = 1;
			$res['message'] 	  = "Invalid Input";
			$res['success'] = false;
			echo json_encode($res);
			exit;
		}
		$this->form_validation->set_rules('email','Email','trim|required');
		if($this->form_validation->run() === FALSE){
			$res['res'] = '';
			$res['errorcode'] =	1;
			$res['message']="Invalid email id";
			$res['success'] = false;
		}else{
			$result2=$this->login_model->get_user_pass($email);
			if(count($result2) > 0){
                $this->login_model->forgotpassword_new_update($email,$newpassword);
				
				$res['email'] = $result2->email;
				$res['message'] 	  = "Password changed successfully.";
				$res['success'] = true;
                               
			}else{
				$res['errorcode']     = 1;
				$res['message'] 	  = "No Record Found";
				$res['success'] = false;
			}
		}
		echo json_encode($res);
	}
	
	
	

     public function hello(){
		$res=array();
		$res['res'] = 'Hello';

		echo json_encode($res);
	}


}
