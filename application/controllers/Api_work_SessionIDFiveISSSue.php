<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(0);
class Api extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model(array('login_model','frntlogin_model'));
		$this->load->helper('email');
	}

	function index(){
		//echo "Randhir Jha";
		exit;
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

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
}

	public function WebRequestSignUp(){
		$res=array();
		$data['first_name'] 	= trim($this->input->post('first_name'));
		$data['last_name'] 		= trim($this->input->post('last_name'));
		$data['password'] 		= $this->encrypt_decrypt('encrypt',$this->input->post('password'));
		$data['email'] 			= $this->input->post('email');
		//print_r($data['email']);
		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
		$this->form_validation->set_rules('password','Password','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');

		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		if (preg_match($regex, $data['email'])) {
			//echo " is a valid email. We can accept it.";
		} else {
		    $rest['errorcode'] =	1;
			$rest['message']="Please provide all parameters with valid email address.";
			$rest['success'] = false;
		}


		if($this->form_validation->run() === FALSE){
			//echo "Hello"; die();
			$rest['errorcode'] =	1;
			$rest['message']="Please provide all parameters with valid email address.";
			$rest['success'] = false;
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
				/**************************Default Session Insert Start*******************************/
				$SessionId = 1;
				$first_session = $this->login_model->getFirstSession($SessionId);
				//echo '<pre>'; print_r($first_session); die();
				$user_session_data['user_id'] = $lastInsert;
				$user_session_data['session_id'] = $first_session->id;
				$user_session_data['pain_level'] = '';
				//$user_session_data['isWatched'] = 0;
				$this->db->insert('user_sessions', $user_session_data);
				/**************************Default Session Insert End*******************************/

				$result = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
				//$rest['id'] = $result->id;
				$rest['first_name'] = $result->first_name;
				$rest['last_name'] = $result->last_name;
				$rest['email'] = $result->email;


				/*****************************Send email to Registered User*******************/
				/*$verification_code=md5(uniqid(rand(), true));
				$savedata=array();
				$savedata['id']=$result->id;
				$savedata['verification_code']=$verification_code;
				$saveverificationcode=$this->login_model->save_verification($savedata);

				if($saveverificationcode)
				{
                    //echo "OOOOOOOOOOOOOOOOOOOOOOOOOO"; die();
					//$verification_link='<a href='.$this->config->item('base_url').'api/verification/'.$verification_code.'>'.$this->config->item('base_url').'api/verification/'.$verification_code.'</a>';


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
						$this->email->from('rakesh@isisdsn.net','Pathways');
						$this->email->to($result->email);
						//$this->email->to('rakesh@radikal-labs.com');

						$this->email->subject('Pathways - Activation email');
						$msg = '<html><body>';
						//$msg .= '<h3 style="color:#f40;">Hi '.$userName.'!</h3>';
						$msg .= '<p>Welcome and thank you for registering.</p>

			                <p><strong>Your account must be activated before you can login. You can activate your account by clicking on below URL or copy URL and paste to your web browser address bar.</strong></p>';
							$msg .= '<p><a href='.$this->config->item('base_url').'signup/verification/'.$verification_code.'>'.$this->config->item('base_url').'signup/verification/'.$verification_code.'</a>
			                </p>
			                <p>Warm Regards <br> Pathways Team</p>';
						$msg .= '</body></html>';
						$this->email->message($msg);

						if (!$this->email->send()){
							//echo "Not send"; die();
							//echo $this->email->print_debugger();
							$messge =$this->email->print_debugger();
						}else{
							//echo "Send"; die();
							 $res['success'] = true;
							 $messge = array('message' => 'Please check your email.');
						}
				}*/





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
					$rest['success'] = false;
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
        //echo '<pre>'; print_r($info); die();
        $userID = $info->user_id;
        $checkUserReceiptToken = $this->login_model->checkUserReceiptTokenExist($userID);
        //echo count($checkUserReceiptToken);
        //echo '<pre>'; print_r($checkUserReceiptToken); die();
        if(count($checkUserReceiptToken)>0) {
			if($checkUserReceiptToken->subscription_id=='com.pathways.lifetimesubcription'){
				//echo "Lifetime";
				$this->login_model->updateUserSubscriptionField($userID);
				//$query = "UPDATE user_tbl SET isSubscribed = '1' WHERE id = '".$userID."'";
				//echo $query; die();
		        //$rs = $this->db->query($query);
			}
			else
			{
				//echo "11111"; die();
				if($checkUserReceiptToken->device_type=='ANDROID'){
				//echo "Android"; die();
				$purchaseToken = $checkUserReceiptToken->receipt_token;
				require_once(APPPATH . "/third_party/purchaseValidation/vendor/autoload.php");
				// google authencation
				//$applicationName = 'PathwaysTest';
				$applicationName = 'Pathways Pain Relief';
				$scope = ['https://www.googleapis.com/auth/androidpublisher'];
				$configLocation = APPPATH.'/third_party/purchaseValidation/pathways_service_account.json';
				//echo $configLocation; die();
				// receipt data
				//$packageName = "com.pathways.pathwayspainrelieftest";
				$packageName = "com.pathways.pathwayspainrelief";
				//$productId = "pathways_test";
				$productId = $checkUserReceiptToken->subscription_id;
				$client = new \Google_Client();
				$client->setApplicationName($applicationName);
				$client->setAuthConfig($configLocation);
				$client->setScopes($scope);
				$service = new Google_Service_AndroidPublisher($client);
				$subscription = $service->purchases_subscriptions->get($packageName, $productId, $purchaseToken);
				//echo '<pre>'; print_r($subscription); die();
				$expiryTimeMillis_Epochtime = $subscription->expiryTimeMillis;
				//echo $expiryTimeMillis_Epochtime; die();
				$expiryDate = date("Y-m-d H:i", substr($expiryTimeMillis_Epochtime, 0, 10));
				$today = date("Y-m-d H:i");
				if(strtotime($expiryDate)<strtotime($today)){
				   //echo "expired"; die();
				   $this->login_model->updateUserSubscriptionExpire($userID);
				}
				$userCancellationTimeMillis = $subscription->userCancellationTimeMillis;
				if($userCancellationTimeMillis!=''){
					$this->login_model->updateUserSubscriptionExpire($userID);
				}
			}
			else if($checkUserReceiptToken->device_type=='IOS'){
				    //echo "IOS"; die();
				    $receipt = $checkUserReceiptToken->receipt_token;
					//echo $receipt; die();
					//echo "USERID:".$userID; die();
					$uid = $userID;
				    function getReceiptData($receipt,$uid)
					{
						$endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
						$ch = curl_init($endpoint);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $receipt);
						$response = curl_exec($ch);
						//$errno = curl_errno($ch);
						//$errmsg = curl_error($ch);
						curl_close($ch);
						//$msg = $response.' - '.$errno.' - '.$errmsg;
						//echo '<pre>'; print_r($response); die();
						$response = json_decode($response);
						//echo '<pre>'; print_r($response); die();
						$expires_dateFetch = $response->receipt->expires_date;
						//$expires_dateFetch = $response->latest_receipt_info->expires_date;
						$expiryDateReceipt = date("Y-m-d H:i", substr($expires_dateFetch, 0, 10));
						$today = date("Y-m-d H:i");
						if(strtotime($expiryDateReceipt)<strtotime($today)){
						   //echo "expired"; die();
						   //echo $uid; die();
						   //$ci = get_instance();
						   //$this->login_model->updateUserSubscriptionExpire($uid);
						   $ci =& get_instance();
						   $ci->db->query("UPDATE user_tbl SET isSubscribed='0' WHERE id='".$uid."'");
						}else{
							$ci =& get_instance();
						    $ci->db->query("UPDATE user_tbl SET isSubscribed='1' WHERE id='".$uid."'");
						}

					}

					$new = '{"receipt-data":"'.$receipt.'","password":"38d3b5ed59e146008de0512789d77e8c"}';
                    getReceiptData($new, $uid);

			    }
		   }

        }else{
			$this->login_model->updateUserSubscriptionExpire($userID);
		}


        if(count($info) > 0) {
            $result2 = $this->login_model->get_full_details('user_tbl','*',array('id' => $info->user_id),array(),array(),0,0,0);
            //echo '<pre>'; print_r($result2); //die();
            /***$subscription_end_dateTime = $result2->subscription_end_date;
            $cuurentDateTime = date('Y-m-d h:i:s');
            if($subscription_end_dateTime>$cuurentDateTime) {

                    $rest['isSubscribed'] = $result2->isSubscribed;
            }
            else
            {
                    $rest['isSubscribed'] = $this->login_model->updateIsSubscribed($info->user_id);
            }***/

            $userDignosisAll = $this->login_model->user_dignosis($info->user_id);
            //echo count($userDignosisAll); die();
            $string_diagnosisIds = '';
            $string_diagnosisTitles = '';
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
			$rest['isSubscribed'] = $result2->isSubscribed;
            $rest['first_name'] = $result2->first_name;
            $rest['last_name'] = $result2->last_name;
            $rest['email'] = $result2->email;
			$rest['free_access'] = $result2->free_access;
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
		$data['last_name'] 				= trim($this->input->post('last_name'));
		$data['facebook_id'] 			= trim($this->input->post('facebook_id'));

		$this->form_validation->set_rules('first_name','First Name','trim|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|required');
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
			//echo '<pre>'; print_r($result); die();

			if(count($result)<1){
				//echo "1"; die();
				$data['is_active'] = '1';
				$this->db->insert('user_tbl', $data);
				$lastInsert = $this->db->insert_id();
				$dvc_data['user_id'] = $lastInsert;
				$dvc_data['access_token'] = $token;
				$this->db->insert('device_tbl', $dvc_data);
				//$result3 = $this->login_model->get_full_details('user_tbl','*',array('id' => $lastInsert),array(),array(),0,0,0);
				/****************Default Session INsert*******************************/
				$SessionId = 1;
				$first_session = $this->login_model->getFirstSession($SessionId);
				//echo '<pre>'; print_r($first_session); die();
				$user_session_data['user_id'] = $lastInsert;
				$user_session_data['session_id'] = $first_session->id;
				$user_session_data['pain_level'] = '';
				//$user_session_data['isWatched'] = 0;
				$this->db->insert('user_sessions', $user_session_data);




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
		$username = trim($this->input->post("email"));
		$this->form_validation->set_rules("email", "Email", "trim|required");

		if ($this->form_validation->run() == FALSE){
			//echo "Not OKKKK"; die();
			$messge = array('message' => 'Please enter correct username.');
			/////$this->session->set_flashdata('item',$messge );
			//redirect($this->config->item('base_url').'admin/login/forgetPassword');
			$res['success'] = false;
		}else{
			//echo "hhhhhOOOOOOKK"; die();
			///if ($this->input->post('btn_forget') == "Submit"){
				$usr_result = $this->login_model->get_forget_password_user($username);
				//echo '<pre>'; print_r($usr_result);
				if(count($usr_result)>0){
					//echo "OK"; die();
					$userId = $usr_result->id;
					$userName = $usr_result->first_name;
					$userEmail = $usr_result->email;
					//$password = $usr_result->password;
					//$password = $this->encrypt_decrypt('decrypt', $password);
					//echo  'Hi '.$userName.", Your Password is '".$password."'";

					//$usersEntity = $this->Users->get($user->id);
					$rnd = $this->random_digit(6);
					$valchk = $this->login_model->rand_reset_user($userId,$rnd);
                    if($valchk==1){
						//echo "1"; die();
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
						$this->email->from('rakesh@isisdsn.net','Pathways');
						$this->email->to($userEmail);
						//$this->email->to('rakesh@radikal-labs.com');

						$this->email->subject('Forget Password');
						$msg = '<html><body>';
						$msg .= '<h3 style="color:#f40;">Hi '.$userName.'!</h3>';
						$msg .= '<p>We received a request to reset the password associated with this email address. If you made this request, please follow the instructions below.</p>

			                <p>If you did not request to have your password reset, you can safely ignore this email. We assure you that your customer account is safe.</p>
			                <p><strong>Click the "Reset Password" button below to reset your password:</strong></p>';
							$msg .= '<p><a href="http://ec2-52-21-2-50.compute-1.amazonaws.com/reset/index/'.$rnd.'" style="width:170px; height:32px; display:inline-block; text-align:center; border-radius:3px; line-height:32px; font-size:18px; font-family:Trebuchet MS, Arial, Helvetica, sans-serif; text-decoration:none; color:#FFF; background:#222;">Reset Password</a>
			                </p>
			                <p>Warm Regards <br> Pathways Team</p>';
						//$msg .= '<p style="color:#080;font-size:18px;">Your password is: '.$password.'</p>';
						$msg .= '</body></html>';
						$this->email->message($msg);

						if (!$this->email->send()){
							//echo $this->email->print_debugger();
							$messge =$this->email->print_debugger();
						}else{
							 $res['success'] = true;
							 $messge = array('message' => 'Please check your email.');
						}
						/////$this->session->set_flashdata('item', $messge);
					}else{
						//echo "2"; die();
						$messge = array('message' => 'Please try again.');
						////$this->session->set_flashdata('item', $messge);
						$res['success'] = false;
					}
				}else{
					//echo "Not OK"; die();
					$messge = array('message' => 'Please enter correct username.');
					///$this->session->set_flashdata('item', $messge);
					$res['success'] = false;

				}

				////$this->load->view('admin/login_template/header');
				////$this->load->view('admin/forgetPassword');
				///$this->load->view('admin/login_template/footer');
			//} else{

			//}
		}
		echo json_encode($res);
	}

	public function getUserSession() {
		$accessToken  = $this->input->post('accessToken');
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
        $userID = $info->user_id;
        //$data['created_by_user_id'] = $userID;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else {
			$check_session = $this->login_model->getUserSessionData($userID);
			//echo '<pre>'; print_r($check_session); die();

			$check_session_unplayed = $this->login_model->getUserSessionDataUnplayed($userID);
			//echo '<pre>'; print_r($check_session_unplayed); die();

			$check_session_played = $this->login_model->getUserSessionDataPlayed($userID);
			//echo '<pre>'; print_r($check_session_played); die();
			//$input = array_map("unserialize", array_unique(array_map("serialize", $check_session_played)));
			//echo '<pre>'; print_r($input); die();
			$temp = array_unique(array_column($check_session_played, 'session_id'));
			$unique_arr = array_intersect_key($check_session_played, $temp);
			//echo '<pre>'; print_r($unique_arr); die();


			$finalData = array_merge($check_session_unplayed,$unique_arr);
			//echo '<pre>'; print_r($finalData); die();

			$temp1 = array_unique(array_column($finalData, 'session_id'));
			$sendSessiondata = array_intersect_key($finalData, $temp1);
			//echo '<pre>'; print_r($sendSessiondata); die();


			$data = array();
			if(count($sendSessiondata) >0) {
                    foreach($sendSessiondata as $val){
						    if($val['session_id']==5) {
								//echo "111111"; die();
								$fifthSessionID = $val['session_id'];

								$sessionData11 = $this->login_model->getFifthSessionInfo($fifthSessionID,$val['diagnosis_id']);
								//echo '<pre>'; print_r($sessionData11);
								if(is_array($sessionData11)) {
								  /*foreach($sessionData11 as $fifthdata){
									  $sessionData2 = $fifthdata;
									  $sessionData2['isWatched'] = $val['isWatched'];
								  }*/
									$sessionData2 = $sessionData11[0];
									$sessionData2['id'] = $sessionData2['session_id'];
									unset($sessionData2['session_id']);
									unset($sessionData2['diagnosis_id']);
									$sessionData2['audio_url'] = $this->config->item('base_url').'uploads/sound_files/'.rawurlencode(basename($sessionData2['audio_url']));

									$sessionData2['session_summary_image'] = $this->config->item('base_url').'uploads/session_images/'.rawurlencode(basename($sessionData2['session_summary_image']));

									$sessionData2['isWatched'] = $val['isWatched'];
									$sessionData2['updated'] = $val['updated'];
								}else{
									$sessionData2 = $sessionData11;
									$sessionData2->id = $sessionData2->session_id;
									unset($sessionData2->session_id);
									unset($sessionData2->diagnosis_id);
									$sessionData2->audio_url = $this->config->item('base_url').'uploads/sound_files/'.rawurlencode(basename($sessionData2->audio_url));
									$sessionData2->session_summary_image = $this->config->item('base_url').'uploads/sound_files/'.rawurlencode(basename($sessionData2->session_summary_image));


									$sessionData2->isWatched = $val['isWatched'];
									$sessionData2->updated = $val['updated'];
								}

								array_push($data,$sessionData2);
								//echo '<pre>'; print_r($data);

							} else{
								//echo "2222"; die();
								$sessionData = $this->login_model->getSpecificSessionInfo($val['session_id']);
								$sessionData[0]['isWatched'] = $val['isWatched'];
								$sessionData[0]['updated'] = $val['updated'];
								$sessionData[0]['audio_url'] = $this->config->item('base_url').'uploads/sound_files/'.rawurlencode(basename($sessionData[0]['audio_url']));
								$sessionData[0]['session_summary_image'] = $this->config->item('base_url').'uploads/session_images/'.rawurlencode(basename($sessionData[0]['session_summary_image']));

								array_push($data,$sessionData[0]);
							}

                    }
					//echo '<pre>'; print_r($data);
                    $res['res']     = $data;
                    $res['success'] 	  = true;
                }
				else{
						$res['success'] 	 = false;
                }



        }
        echo json_encode($res);
	}

	public function PlaySession() {
		$accessToken  = $this->input->post('accessToken');
		$session_id= $this->input->post('session_id');

        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
        $userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{

			$playSession = $this->login_model->checkSessionExist($userID,$session_id);
			if($playSession > 0){
			 $this->login_model->update_Session_iswatched($userID,$session_id);
			}

			if($this->AddPainIncreaseDecrease($userID, $session_id)){
				//echo "Function called"; die();
				if($session_id>=16 AND $session_id<=64) {
				//echo "Session ID 15"; die();
					$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
					//echo '<pre>'; print_r($unwatchedSessionCount); die();
					$unwatchedSessionCount_value = count($unwatchedSessionCount);
					//echo $unwatchedSessionCount_value; die();
					$maxSessionTibeAdded = 5-$unwatchedSessionCount_value;
					$LastSessionIDValue = $this->login_model->getLastSessionIDValue($userID);
					//echo '<pre>'; print_r($LastSessionIDValue); die();
					$LastSessionIDValueFetch = $LastSessionIDValue->session_id;
					if($maxSessionTibeAdded > 0){
						$start = $LastSessionIDValueFetch+1;
						$max = $start+($maxSessionTibeAdded-1);
						for($start; $start<=$max;$start++)
						{
							if($session_id==$start){
								//echo "Exist";

							} else{
								//echo "Not exist";
								//$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
								//$unwatchedSessionCount_value = count($unwatchedSessionCount);
								//echo $unwatchedSessionCount_value; die();
								//if($unwatchedSessionCount_value>4){
									//echo "sdfsdfsd"; die();
								//} else{
									if($start<=64){
										$nextsessiondata['user_id']  = $userID;
										$nextsessiondata['session_id']  =  $start;
										$this->db->insert('user_sessions', $nextsessiondata);
									}
								//}

							}
							$res['success'] 	  = true;
						}
					}else{
						$res['success'] 	  = true;
					}

			}

				$res['success'] 	  = true;
			}else{
				//echo "Else part executed"; die();
				if($session_id==4) {
			   $getUserdiagnosis = $this->login_model->getUserdiagnosis($userID);
			   //echo '<pre>'; print_r($getUserdiagnosis); die();
               if(count($getUserdiagnosis)==0){
				  // echo "12111"; die();
				   $ftech_Pain_diagnsisInfo = 0;
			   } else{
				   //echo "ASasASas"; die();
				   $Userdiagnosis_ID = $getUserdiagnosis->diagnosis_id;
				   $ftech_Pain_diagnsisInfo = $this->login_model->ftech_Pain_diagnsisInfo($Userdiagnosis_ID);
				   echo '<pre>'; print_r($ftech_Pain_diagnsisInfo); die();
			   }

			   $lastSessionInfo = $this->login_model->getLastSessionIDValue($userID);
			   //echo '<pre>'; print_r($lastSessionInfo); die();
			   if($ftech_Pain_diagnsisInfo)
			   {
				   //echo "found"; die();
				   /*$data['user_id'] = $userID;
				   $data['session_id'] = $ftech_Pain_diagnsisInfo->session_id;
				   $this->db->insert('user_sessions', $data);
				   $res['success'] 	  = true;*/
				    $playSession = $this->login_model->checkSessionExist($userID,$session_id);
					//echo '<pre>'; print_r($playSession); die();
					if($playSession > 0){
					$this->login_model->update_Session_iswatched($userID,$session_id);
					$nextSessionID = $ftech_Pain_diagnsisInfo->session_id;
					if($session_id<$lastSessionInfo->session_id) {
                       //echo "11"; die();
					} else{
						//echo "222"; die();
						if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){

						} else{
							$nextsessiondata['user_id']  = $userID;
							$nextsessiondata['session_id']  =  $nextSessionID;
							$nextsessiondata['diagnosis_id']  =  $ftech_Pain_diagnsisInfo->diagnosis_id;
							$this->db->insert('user_sessions', $nextsessiondata);
						}

					}

						$res['success'] 	  = true;
					} else{
						/*$data['user_id'] = $userID;
						$data['session_id'] = $session_id;
						$this->db->insert('user_sessions', $data);
						$res['success'] 	  = false;*/
					}

			   } else{
				   //echo "33333"; die();
				   /*$data['user_id'] = $userID;
				   $data['session_id'] = $session_id+2;
				   $this->db->insert('user_sessions', $data);
				   $res['success'] 	  = true;*/
				   $this->login_model->update_Session_iswatched($userID,$session_id);
				   $nextSessionID = $session_id+2;
				   if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){
					   //echo "asASAs"; die();
					} else{
						//echo "OKKKKKK"; die();
						$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
						$unwatchedSessionCount_value = count($unwatchedSessionCount);
						//echo $unwatchedSessionCount_value; die();
						if($unwatchedSessionCount_value>0){
							//echo "sdfsdfsd"; die();
						}else{
							$nextsessiondata['user_id']  = $userID;
							$nextsessiondata['session_id']  =  $nextSessionID;
							$this->db->insert('user_sessions', $nextsessiondata);
						}


					}
				   $res['success'] 	  = true;
			   }

			}
			/*else if($session_id>=15 AND $session_id<=62) {
				//echo "Session ID 15"; die();
				$playSession = $this->login_model->checkSessionExist($userID,$session_id);
				if($playSession > 0){
				 $this->login_model->update_Session_iswatched($userID,$session_id);
				}
				//echo "Session greater then fifteen"; //die();
				$maxSessionID = $session_id+5;
				for($sid=$session_id+1; $sid<=$maxSessionID; $sid++)
				{
						if($this->login_model->checkSessionExist($userID,$sid) > 0){
							//echo "111"; //die();
							//$this->login_model->update_Session_iswatched($userID,$session_id);
						}
						else{
							//echo "222"; //die();
							$nextsessiondata['user_id']  = $userID;
							$nextsessiondata['session_id']  =  $sid;
							$this->db->insert('user_sessions', $nextsessiondata);
						}

						$res['success'] 	  = true;
				}

			}*/

			else if($session_id>=16 AND $session_id<=64) {//////$session_id<=64
				//echo "Session ID 16"; die();
				$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
				//echo '<pre>'; print_r($unwatchedSessionCount); die();
				$unwatchedSessionCount_value = count($unwatchedSessionCount);
				//echo $unwatchedSessionCount_value; die();
				$maxSessionTibeAdded = 5-$unwatchedSessionCount_value;
				$LastSessionIDValue = $this->login_model->getLastSessionIDValue($userID);
				//echo '<pre>'; print_r($LastSessionIDValue); die();
				$LastSessionIDValueFetch = $LastSessionIDValue->session_id;
				if($maxSessionTibeAdded > 0){
					$start = $LastSessionIDValueFetch+1;
					$max = $start+($maxSessionTibeAdded-1);
					for($start; $start<=$max;$start++)
					{
						if($session_id==$start){
							//echo "Exist";

						} else{
							//echo "Not exist";
							$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
							$unwatchedSessionCount_value = count($unwatchedSessionCount);
							//echo $unwatchedSessionCount_value; die();
							if($unwatchedSessionCount_value>4){
								//echo "sdfsdfsd"; die();
							} else{
								if($start<=64){
								   $nextsessiondata['user_id']  = $userID;
								   $nextsessiondata['session_id']  =  $start;
								   $this->db->insert('user_sessions', $nextsessiondata);
								}

							}

						}
						$res['success'] 	  = true;
					}
				}else{
					$res['success'] 	  = true;
				}

			}

			else if($session_id<=64) {
				//echo "Less than 62"; die();
				$playSession = $this->login_model->checkSessionExist($userID,$session_id);
				if($playSession > 0){
				$this->login_model->update_Session_iswatched($userID,$session_id);
				$nextSessionID = $session_id+1;

				$LastSessionIDValue = $this->login_model->getLastSessionIDValue($userID);
				//echo '<pre>'; print_r($LastSessionIDValue); //die();
				$LastSessionIDValueFetch = $LastSessionIDValue->session_id;

				if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){
					  //echo "dasdasd"; die();
					} else{
						//echo "22222"; die();
						$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
						$unwatchedSessionCount_value = count($unwatchedSessionCount);
						if($unwatchedSessionCount_value>0){
							//echo "sdfsdfsd"; die();
						}else{
							if($LastSessionIDValueFetch==64){

							}else{
								$nextsessiondata['user_id']  = $userID;
								$nextsessiondata['session_id']  =  $nextSessionID;
								$this->db->insert('user_sessions', $nextsessiondata);
							}

						}

					}

				    $res['success'] 	  = true;
				} else{
					$data['user_id'] = $userID;
					$data['session_id'] = $session_id;
					$this->db->insert('user_sessions', $data);
					$res['success'] 	  = true;
				}
			}

			else if($session_id>64) {
				//echo "Greater than 62"; die();
				$playSession = $this->login_model->checkSessionExist($userID,$session_id);
				//echo $playSession; die();
				if($playSession > 0){
				$this->login_model->update_Session_iswatched($userID,$session_id);
				$LastSessionIDValue = $this->login_model->getLastSessionIDValue($userID);
				//echo '<pre>'; print_r($LastSessionIDValue); die();
				$LastSessionIDValueFetch = $LastSessionIDValue->session_id;
				if($LastSessionIDValueFetch==4) {
			      //echo "Session ID 4"; die();
				   $getUserdiagnosis = $this->login_model->getUserdiagnosis($userID);
				   //echo '<pre>'; print_r($getUserdiagnosis); die();
				   $Userdiagnosis_ID = $getUserdiagnosis->diagnosis_id;
				   $ftech_Pain_diagnsisInfo = $this->login_model->ftech_Pain_diagnsisInfo($Userdiagnosis_ID);
				   //echo '<pre>'; print_r($ftech_Pain_diagnsisInfo); die();
				   ////$lastSessionInfo = $this->login_model->lastSessionInfoFetch($userID);
				   $lastSessionInfo = $this->login_model->getLastSessionIDValue($userID);

				   //echo '<pre>'; print_r($lastSessionInfo); die();

				   if(count($ftech_Pain_diagnsisInfo) > 0)
				   {
					   //echo "222222";
					   /*$data['user_id'] = $userID;
					   $data['session_id'] = $ftech_Pain_diagnsisInfo->session_id;
					   $this->db->insert('user_sessions', $data);
					   $res['success'] 	  = true;*/
						$playSession = $this->login_model->checkSessionExist($userID,$session_id);
						//echo '<pre>'; print_r($playSession); die();
						if($playSession > 0){
						$this->login_model->update_Session_iswatched($userID,$session_id);
						$nextSessionID = $ftech_Pain_diagnsisInfo->session_id;
						if($session_id<$lastSessionInfo->session_id) {

						} else{
							if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){

							} else{
								$nextsessiondata['user_id']  = $userID;
								$nextsessiondata['session_id']  =  $nextSessionID;
								$nextsessiondata['diagnosis_id']  =  $ftech_Pain_diagnsisInfo->diagnosis_id;
								$this->db->insert('user_sessions', $nextsessiondata);
							}

						}

							$res['success'] 	  = true;
						} else{
							/*$data['user_id'] = $userID;
							$data['session_id'] = $session_id;
							$this->db->insert('user_sessions', $data);
							$res['success'] 	  = false;*/
						}

				   } else{
					   //echo "33333"; die();
					   /*$data['user_id'] = $userID;
					   $data['session_id'] = $session_id+2;
					   $this->db->insert('user_sessions', $data);
					   $res['success'] 	  = true;*/
					   $this->login_model->update_Session_iswatched($userID,$session_id);
					   $nextSessionID = $LastSessionIDValueFetch+2;
					   if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){

						} else{

							$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
							$unwatchedSessionCount_value = count($unwatchedSessionCount);
							//echo $unwatchedSessionCount_value; die();
							if($unwatchedSessionCount_value>0){
								//echo "Unwatched session greater than 0"; die();
								//$res['success'] 	  = true;
							}else{
								$nextsessiondata['user_id']  = $userID;
								$nextsessiondata['session_id']  =  $nextSessionID;
								$this->db->insert('user_sessions', $nextsessiondata);
							}

						}
					   $res['success'] 	  = true;
				   }

			} else if($LastSessionIDValueFetch>=16 AND $LastSessionIDValueFetch<=64) {
				//echo "Session ID 15"; die();
				$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
				//echo '<pre>'; print_r($unwatchedSessionCount); die();
				$unwatchedSessionCount_value = count($unwatchedSessionCount);
				//echo $unwatchedSessionCount_value; die();
				$maxSessionTibeAdded = 5-$unwatchedSessionCount_value;
				//$LastSessionIDValue = $this->login_model->getLastSessionIDValue($userID);
				//echo '<pre>'; print_r($LastSessionIDValue); die();
				//$LastSessionIDValueFetch = $LastSessionIDValue->session_id;
				if($maxSessionTibeAdded > 0){
					//echo "sdasdas"; die();
					$start = $LastSessionIDValueFetch+1;
					$max = $start+($maxSessionTibeAdded-1);
					for($start; $start<=$max;$start++)
					{
						if($LastSessionIDValueFetch==$start){
							//echo "Exist";

						} else{
							//echo "Not exist";
							$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
							$unwatchedSessionCount_value = count($unwatchedSessionCount);
							//echo $unwatchedSessionCount_value; die();
							if($unwatchedSessionCount_value>4){
								//echo "sdfsdfsd"; die();
							} else{
								$nextsessiondata['user_id']  = $userID;
								$nextsessiondata['session_id']  =  $start;
								$this->db->insert('user_sessions', $nextsessiondata);
							}

						}
						$res['success'] 	  = true;
					}
				}else{
					//echo "adasdasd"; die();
					$res['success'] 	  = true;
				}

			}


			else {
				//echo "Not 4"; die();
				$nextSessionID = $LastSessionIDValueFetch+1;
				if($this->login_model->checkSessionExist($userID,$nextSessionID) > 0){
					  //echo "fsdfsdf"; die();
					} else{
						//echo "OK"; //die();
						$unwatchedSessionCount = $this->login_model->NumberOfUnwatchedSEssion($userID);
						$unwatchedSessionCount_value = count($unwatchedSessionCount);
						//echo $unwatchedSessionCount_value; die();
						if($unwatchedSessionCount_value>0){
							//echo "sdfsdfsd"; die();
						}else{
							//echo "aaaaa"; die();
							$nextsessiondata['user_id']  = $userID;
						    $nextsessiondata['session_id']  =  $nextSessionID;
						    $this->db->insert('user_sessions', $nextsessiondata);
						}

					}

				    $res['success'] 	  = true;
				}
			}


			}

		}


		}
		echo json_encode($res);
	}


	public function AddPainIncreaseDecrease($userID, $session_id) {
		//echo "Ok"; die();
		//echo $userID;
		//echo $session_id; die();
		$flag = false;
		$numberofAveragePain = $this->login_model->numberofAveragePain($userID);
		//echo count($numberofAveragePain); die();
		if(count($numberofAveragePain)>1) {
			//echo "111"; die();
			$LastConsiderationPointVal = $this->login_model->getLastConsiderationPointVal($userID);
			//echo '<pre>'; print_r($LastConsiderationPointVal); die();
			if($LastConsiderationPointVal->last_consideration_value==NULL) {
				//echo "NULL"; die();
				$RS_AVERAGE_RATES = $this->login_model->lastTwoAverageRatePain($userID);
				//echo '<pre>'; print_r($RS_AVERAGE_RATES); die();
				$FirstDate_pain_created = date('Y-m-d', strtotime($RS_AVERAGE_RATES[1]['created']));
				$LastDate_pain_created = date('Y-m-d', strtotime($RS_AVERAGE_RATES[0]['created']));
				$Datedifference_Pain_created = round(abs(strtotime($LastDate_pain_created) - strtotime($FirstDate_pain_created))/86400);
				//echo $Datedifference_Pain_created; die();
				if($Datedifference_Pain_created>=1) {
				    //echo "OK"; die();
					$SIGNIFICANT_RISE_FALL = $RS_AVERAGE_RATES[0]['average_pain']-$RS_AVERAGE_RATES[1]['average_pain'];
					//echo $SIGNIFICANT_RISE_FALL; die();
					if($SIGNIFICANT_RISE_FALL>=+3){
						//echo "+3"; die();
						$FirstPainIncreaseSessionID = 65;
						$LAST_PAIN_INCREASE_SESSION_ID = $this->login_model->getlastIncreasePainSessionID($userID,$FirstPainIncreaseSessionID);
						//echo '<pre>'; print_r($LAST_PAIN_INCREASE_SESSION_ID); //die();
						//echo count($LAST_PAIN_INCREASE_SESSION_ID); die();
						if(count($LAST_PAIN_INCREASE_SESSION_ID) ==0){
							//echo "1"; die();
							//$FirstPainIncreaseSessionID = 63;
							$FirstPainIncreaseData['user_id'] = $userID;
							$FirstPainIncreaseData['session_id'] = $FirstPainIncreaseSessionID;
							$this->db->insert('user_sessions', $FirstPainIncreaseData);
							$flag = true;
						} else{
							//echo "2"; die();
							$LAST_PAIN_INCREASE_SESSION_ID1 = $this->login_model->getlastIncreasePainSessionID($userID,$FirstPainIncreaseSessionID+1);

							if(count($LAST_PAIN_INCREASE_SESSION_ID1) ==0){
								$NEXT_PAIN_INCREASE_SESSION_ID = $LAST_PAIN_INCREASE_SESSION_ID[0]['session_id']+1;
								$NEXT_PAIN_INCREASE_SESSIONData['user_id']  = $userID;
								$NEXT_PAIN_INCREASE_SESSIONData['session_id']  =  $NEXT_PAIN_INCREASE_SESSION_ID;
								$this->db->insert('user_sessions', $NEXT_PAIN_INCREASE_SESSIONData);
								$flag = true;
							} else{
								//echo "2"; die();
								$NEXT_PAIN_INCREASE_SESSION_ID = $LAST_PAIN_INCREASE_SESSION_ID1[0]['session_id']+1;
								$NEXT_PAIN_INCREASE_SESSIONData['user_id']  = $userID;
								$NEXT_PAIN_INCREASE_SESSIONData['session_id']  =  $NEXT_PAIN_INCREASE_SESSION_ID;
								$this->db->insert('user_sessions', $NEXT_PAIN_INCREASE_SESSIONData);
								$flag = true;
							}
						}

					}


					else if($SIGNIFICANT_RISE_FALL<=-3){
						//echo "-3"; die();
						//echo "SIGNIFICANT_RISE_FALL".$SIGNIFICANT_RISE_FALL; die();
						$FirstPainDecreaseSessionID = 68;
						$LAST_PAIN_Decrease_SESSION_ID = $this->login_model->getlastDecreasePainSessionID($userID,$FirstPainDecreaseSessionID);
						//echo '<pre>'; print_r($LAST_PAIN_Decrease_SESSION_ID); //die();
						//echo count($LAST_PAIN_Decrease_SESSION_ID); die();
						if(count($LAST_PAIN_Decrease_SESSION_ID) ==0){
							//echo "1"; die();
							//$FirstPainIncreaseSessionID = 63;
							$FirstPainDecreaseData['user_id'] = $userID;
							$FirstPainDecreaseData['session_id'] = $FirstPainDecreaseSessionID;
							$this->db->insert('user_sessions', $FirstPainDecreaseData);
							$flag = true;
						} else{
							//echo "2"; die();
							$LAST_PAIN_Decrease_SESSION_ID1 = $this->login_model->getlastDecreasePainSessionID($userID,$FirstPainDecreaseSessionID+1);
							if(count($LAST_PAIN_Decrease_SESSION_ID1) ==0){
							//echo "1"; die();
							//$FirstPainIncreaseSessionID = 63;
							$FirstPainDecreaseData['user_id'] = $userID;
							$FirstPainDecreaseData['session_id'] = $FirstPainDecreaseSessionID;
							$this->db->insert('user_sessions', $FirstPainDecreaseData);
							$flag = true;
						  }else{

							$NEXT_PAIN_Decrease_SESSION_ID = $LAST_PAIN_Decrease_SESSION_ID1[0]['session_id']+1;
							$NEXT_PAIN_Decrease_SESSION_Data['user_id']  = $userID;
						    $NEXT_PAIN_Decrease_SESSION_Data['session_id']  =  $NEXT_PAIN_Decrease_SESSION_ID;
						    $this->db->insert('user_sessions', $NEXT_PAIN_Decrease_SESSION_Data);
							$flag = true;
						  }

						}

					}

					$RS_AVERAGE_RATES = $this->login_model->lastTwoAverageRatePain($userID);
					//echo '<pre>'; print_r($RS_AVERAGE_RATES); die();
					$LastConsideration_Value = $RS_AVERAGE_RATES[0]['average_pain'];
					$LastConsideration_Date = $RS_AVERAGE_RATES[0]['created'];
					$this->login_model->updateLastConsidrationValueDate($userID,$LastConsideration_Value,$LastConsideration_Date);


				}
			}
			else{
				//echo "Not Null"; die();
				$getUserLastConsiderationDate = $this->login_model->getLastConsiderationPointVal($userID);
				//echo '<pre>'; print_r($getUserLastConsiderationDate); die();
				$getUserLastConsiderationDate_value = $getUserLastConsiderationDate->last_consideration_date;
				$RS_AVERAGE_RATES_LastConsideration = $this->login_model->getAverageRatePain_LastConsidearionDate($userID,$getUserLastConsiderationDate_value);
				//echo '<pre>'; print_r($RS_AVERAGE_RATES_LastConsideration); die();
				if(count($RS_AVERAGE_RATES_LastConsideration)>0){
					//if($Datedifference_Pain_created>=5) {
						//echo "OK"; die();
						$SIGNIFICANT_RISE_FALL = $RS_AVERAGE_RATES_LastConsideration[0]['average_pain']-$getUserLastConsiderationDate->last_consideration_value;
						//echo $SIGNIFICANT_RISE_FALL; die();
						if($SIGNIFICANT_RISE_FALL>=+3){
							//echo "+3"; die();
							$FirstPainIncreaseSessionID = 65;
							//$LAST_PAIN_INCREASE_SESSION_ID = $this->login_model->getlastIncreasePainSessionID($userID,$FirstPainIncreaseSessionID);
							//echo '<pre>'; print_r($LAST_PAIN_INCREASE_SESSION_ID); //die();
							//echo count($LAST_PAIN_INCREASE_SESSION_ID); die();

							$LAST_PAIN_INCREASE_SESSION_ID = $this->login_model->getLastSessionIDValuePainIncreaseDescr($userID);
							//echo '<pre>'; print_r($LAST_PAIN_INCREASE_SESSION_ID);
							//echo count($LAST_PAIN_INCREASE_SESSION_ID); die();
							if(count($LAST_PAIN_INCREASE_SESSION_ID) ==0){
								//echo "111"; die();
								$this->login_model->deleteLastPainIncreaseDescSession($userID);


								$FirstPainIncreaseData['user_id'] = $userID;
								$FirstPainIncreaseData['session_id'] = $FirstPainIncreaseSessionID;
								$this->db->insert('user_sessions', $FirstPainIncreaseData);
								$flag = true;
							} else{
								//echo "2222"; die();
								$NEXT_PAIN_INCREASE_SESSION_ID = $LAST_PAIN_INCREASE_SESSION_ID->session_id+1;
								if($LAST_PAIN_INCREASE_SESSION_ID->session_id==67){
									//echo "67"; die();
									$this->login_model->deleteLastPainIncreaseDescSession($userID);

									$FirstPainIncreaseData['user_id'] = $userID;
									$FirstPainIncreaseData['session_id'] = $FirstPainIncreaseSessionID;
									$this->db->insert('user_sessions', $FirstPainIncreaseData);
									$flag = true;

								}else{
								    //echo "Not 67"; die();
									$this->login_model->deleteLastPainIncreaseDescSession($userID);

									$NEXT_PAIN_INCREASE_SESSIONData['user_id']  = $userID;
									$NEXT_PAIN_INCREASE_SESSIONData['session_id']  =  $NEXT_PAIN_INCREASE_SESSION_ID;
									$this->db->insert('user_sessions', $NEXT_PAIN_INCREASE_SESSIONData);
									$flag = true;
								}



								/******$LAST_PAIN_INCREASE_SESSION_ID = $this->login_model->getLastSessionIDValuePainIncreaseDescr($userID);
								//echo '<pre>'; print_r($LAST_PAIN_INCREASE_SESSION_ID); die();
								if($LAST_PAIN_INCREASE_SESSION_ID->session_id==67){
									//echo "saasdasdas"; die();
									$this->login_model->updateSessionPainIncreDecrRequest($LAST_PAIN_INCREASE_SESSION_ID->id,$FirstPainIncreaseSessionID);
									$flag = true;
								} else{
									$LastSessionRowID = $LAST_PAIN_INCREASE_SESSION_ID->id;
									$NEXT_PAIN_INCREASE_SESSION_ID = $LAST_PAIN_INCREASE_SESSION_ID->session_id+1;
									//echo $NEXT_PAIN_INCREASE_SESSION_ID; die();
									$this->login_model->updateSessionPainIncreDecrRequest($LastSessionRowID,$NEXT_PAIN_INCREASE_SESSION_ID);
									$flag = true;
								}***///


							}


						}
						else if($SIGNIFICANT_RISE_FALL<=-3){
							//echo "-3"; die();
							//echo "SIGNIFICANT_RISE_FALL".$SIGNIFICANT_RISE_FALL; die();
							$FirstPainDecreaseSessionID = 68;
							$LAST_PAIN_Decrease_SESSION_ID = $this->login_model->getlastDecreasePainSessionGetValue($userID);
							//echo '<pre>'; print_r($LAST_PAIN_Decrease_SESSION_ID); //die();
							//echo count($LAST_PAIN_Decrease_SESSION_ID); die();
							$flag = true;
							if(count($LAST_PAIN_Decrease_SESSION_ID) ==0){
								//echo "1111"; die();
								//$FirstPainIncreaseSessionID = 63;

								$this->login_model->deleteLastPainIncreaseDescSession($userID);
								$FirstPainDecreaseData['user_id'] = $userID;
								$FirstPainDecreaseData['session_id'] = $FirstPainDecreaseSessionID;
								$this->db->insert('user_sessions', $FirstPainDecreaseData);
							} else{
								//echo "2222"; die();
								if($LAST_PAIN_Decrease_SESSION_ID->session_id==70){
									$this->login_model->deleteLastPainIncreaseDescSession($userID);

									$FirstPainDecreaseData['user_id'] = $userID;
								    $FirstPainDecreaseData['session_id'] = $FirstPainDecreaseSessionID;
								    $this->db->insert('user_sessions', $FirstPainDecreaseData);

								} else{
									$this->login_model->deleteLastPainIncreaseDescSession($userID);
									$NEXT_PAIN_Decrease_SESSION_ID = $LAST_PAIN_Decrease_SESSION_ID->session_id+1;
									$NEXT_PAIN_Decrease_SESSION_Data['user_id']  = $userID;
									$NEXT_PAIN_Decrease_SESSION_Data['session_id']  =  $NEXT_PAIN_Decrease_SESSION_ID;
									$this->db->insert('user_sessions', $NEXT_PAIN_Decrease_SESSION_Data);
								}



								/*****$NEXT_PAIN_Decrease_SESSION_ID = $this->login_model->getlastDecreasePainSessionGetValue($userID);
								//echo '<pre>'; print_r($NEXT_PAIN_Decrease_SESSION_ID); die();
								if($NEXT_PAIN_Decrease_SESSION_ID->session_id==70){
									$this->login_model->updateSessionPainIncreDecrRequest($NEXT_PAIN_Decrease_SESSION_ID->id,$FirstPainDecreaseSessionID);
									$flag = true;
								}else{
									//echo "sfdasdasdas"; die();
									$LastSessionRowID = $NEXT_PAIN_Decrease_SESSION_ID->id;
									$NEXT_PAIN_Decrease_SESSION_ID = $NEXT_PAIN_Decrease_SESSION_ID->session_id+1;
									//echo $NEXT_PAIN_INCREASE_SESSION_ID; die();
									$this->login_model->updateSessionPainIncreDecrRequest($LastSessionRowID,$NEXT_PAIN_Decrease_SESSION_ID);
									$flag = true;
								}****/

							}


						}

						$RS_AVERAGE_RATES = $this->login_model->lastTwoAverageRatePainNEW($userID,$getUserLastConsiderationDate_value);
						//echo '<pre>'; print_r($RS_AVERAGE_RATES); die();
						$LastConsideration_Value = $RS_AVERAGE_RATES[0]['average_pain'];
						$LastConsideration_Date = $RS_AVERAGE_RATES[0]['created'];
						$this->login_model->updateLastConsidrationValueDate($userID,$LastConsideration_Value,$LastConsideration_Date);

					//}
				} else {

				}
			}
		}
		return $flag;
	}



	public function SubscribeUser() {
		$accessToken  = $this->input->post('accessToken');
		$subcriptionCode= $this->input->post('subcriptionCode');

        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
        $userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{
			$subscribe_user = $this->login_model->update_subscribe_user($userID,$subcriptionCode);
		    if($subscribe_user==1){

				$res['success'] 	  = true;
			} else{

				$res['success'] 	  = false;
			}

		}
		echo json_encode($res);
	}


	public function subscriptionPurchase() {
                        $receiptToken= $this->input->post('receiptToken');
                        $accessToken  = $this->input->post('accessToken');
						$device_type  = $this->input->post('device_type');
                        $subscriptionID = $this->input->post('subscriptionID');
						$info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
						//echo '<pre>'; print_r($info);
						$userID = $info->user_id;
                        $checkUserReceiptToken = $this->login_model->checkUserReceiptTokenExist($userID);
                        //echo count($checkUserReceiptToken);
                        //echo '<pre>'; print_r($checkUserReceiptToken); die();
                        if(count($checkUserReceiptToken)==0){
                            $data['receipt_token'] = $receiptToken;
                            $data['user_id'] = $userID;
                            $data['device_type'] = $device_type;
                            $data['subscription_id'] = $subscriptionID;
                            $data['purchase_date'] = date('Y-m-d H:i:s');
                            $this->db->insert('subscription_purchase_details', $data);
                            $this->login_model->updateUserSubscriptionField($userID);
                            $res['receiptToken'] 	  = $receiptToken;
                            $res['success'] 	  = true;
                            echo json_encode($res);
                        }else {
                            $this->login_model->updateUserReceiptToken($userID,$receiptToken,$device_type,$subscriptionID);
                            $res['receiptToken'] 	  = $receiptToken;
                            $res['success'] 	  = true;
                            echo json_encode($res);

                            /*****error_reporting(E_ALL | E_STRICT);
                            ini_set('display_errors', 1);
                            require_once(APPPATH . "/third_party/purchaseValidation/vendor/autoload.php");
                            // google authencation
                            $applicationName = 'PathwaysTest';
                            $scope = ['https://www.googleapis.com/auth/androidpublisher'];
                            $configLocation = APPPATH.'/third_party/purchaseValidation/pathways_service_account.json';
                            //echo $configLocation; die();
                            // receipt data
                            $packageName = "com.pathways.pathwayspainrelieftest";
                            $productId = "pathways_test";
                            $purchaseToken = "ejgojebffoajcnelfnpfdhhc.AO-J1OxAHHy3AfueyNyLpJoZxb29G_IpZ3OzGxsrjaR1EuWalcjN-0xupA-ctV2Sbgm_Z8VOUssBvubl902AAcFePtZMDC720cJmH2HcCsjrxA_saeD9KnwPHLxgj05NdYjFwo8-3AIyH7Go9Vnu9hezsQk_G-NSYg";   ///////////After cancel subscription then again continue Subscrption
                            $client = new \Google_Client();
                            $client->setApplicationName($applicationName);
                            $client->setAuthConfig($configLocation);
                            $client->setScopes($scope);
                            try{
                                    $service = new Google_Service_AndroidPublisher($client);
                                    $subscription = $service->purchases_subscriptions->get($packageName, $productId, $purchaseToken);
                                    echo '<pre>'; print_r($subscription); //die();
                                    $expiryTimeMillis_Epochtime = $subscription->expiryTimeMillis;
                                    //echo $expiryTimeMillis_Epochtime; die();
                                    echo $expiryDate = date("Y-m-d H:i", substr($expiryTimeMillis_Epochtime, 0, 10));
                                    echo $today = date("Y-m-d H:i");
                                    if(strtotime($expiryDate)<strtotime($today)){
                                       //echo "expired"; die();
                                       $this->login_model->updateUserSubscriptionExpire($userID);
                                    }

                            }catch(Exception $e){
                                echo 'got error = ' . $e->getMessage() . PHP_EOL;
                            }*****/

                        }





			/*$getReceiptToken = $this->login_model->getReceiptTokenData($userID);
			echo '<pre>'; print_r($getReceiptToken); die();
			$FetchReceiptToken = $getReceiptToken[0]['receipt_token'];
			$json['receipt-data'] = base64_encode($FetchReceiptToken);
			$post = json_encode($json);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://sandbox.itunes.apple.com/verifyReceipt");
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$result=curl_exec ($ch);
			curl_close ($ch);
			print_r($result); die();*/

	}


public function AddUserPain() {
        $accessToken  = $this->input->post('accessToken');
        $userPain= $this->input->post('userPain');
        $info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
        $userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{
			    $user_pain_hit = $this->login_model->user_pain_num_hit($userID);
				//echo '<pre>'; print_r($user_pain_hit); die();

				if(count($user_pain_hit) > 0) {
					//echo "1"; die();
					$painDateval = $user_pain_hit->pain_date;
					$data['pain_level'] = ($user_pain_hit->pain_level)+$userPain;
					$data['num_hit'] = ($user_pain_hit->num_hit)+1;
					$data['pain_date'] = ($user_pain_hit->pain_date);
					//print_r($data); die();
					$this->login_model->user_pain_num_hit_update($userID, $data,$painDateval);
					$res['success'] = true;
				} else {
					//echo "2"; die();
					$data['user_id'] = $userID;
					$data['pain_level'] = $userPain;
					$data['pain_date'] = date('Y-m-d');
					$data['num_hit'] = 1;

					$this->db->insert('user_pains', $data);
					$res['success'] 	  = true;
				}


		}
		echo json_encode($res);
	}


	public function CanGiveFeedback() {
		$accessToken  = $this->input->post('accessToken');
		$info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
		$userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{
			    $user_pain_Info = $this->login_model->user_pain_created_time($userID);
				//echo '<pre>'; print_r($user_pain_Info); die();
				//echo count($user_pain_Info); die();
				if(count($user_pain_Info) > 0) {
					//echo "1"; die();
					$user_pain_created_time = $user_pain_Info->created;
					//echo "PainCreated:".$user_pain_created_time; echo '<br>';
					$cuurentDateTime = date('Y-m-d h:i:s');
					//echo "CUrrentTime:".$cuurentDateTime;

					$date1 = $user_pain_created_time;
					$date2 = $cuurentDateTime;
					//Convert them to timestamps.
					$date1Timestamp = strtotime($date1);
					$date2Timestamp = strtotime($date2);

					//Calculate the difference.
					$difference = $date2Timestamp - $date1Timestamp;
					$difference_inhour = round($difference/3600, 2);
					//echo "DiiferenceHour".$difference_inhour; //die();

					if($difference_inhour<6){
						//echo "Less than 6 hrs";
						$res['difference_inhour'] = $difference_inhour;
						$res['cangive'] = false;
						$res['success'] = false;

					}else {
						//echo "Greater than 6 hrs";
						$res['cangive'] = true;
						$res['success'] = true;
						$res['difference_inhour'] = $difference_inhour;
					}
				} else {
					//echo "2"; die();
					$res['cangive'] = true;
					$res['success'] = true;
					$res['difference_inhour'] = 0;
				}

				//$res['success'] 	  = true;

		}
		echo json_encode($res);
	}


	public function GetUserStatistics() {
		$accessToken  = $this->input->post('accessToken');
		$info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info);
		$userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{
			    $user_pain_Info = $this->login_model->GetUserStatisticsList($userID);
				if(count($user_pain_Info) >0) {
                    $p=0;
                    foreach($user_pain_Info as $val){
							$res2[$p]['id'] = $val['id'];
                            $res2[$p]['painLevel'] = $val['pain_level'];
                            $res2[$p]['sessionDate'] = $val['pain_date'];
							$res2[$p]['hitCount'] = $val['num_hit'];
                            $p++;
                    }
                    $res['res']     = $res2;
                    $res['success'] 	  = true;
                }else{

                        $res['success'] 	 = true;
                }

		}
		echo json_encode($res);
	}


	public function SendUserFeedBack() {
		$accessToken  = $this->input->post('accessToken');
		$userComment  = $this->input->post('userComment');
		$info = $this->login_model->useraccessTokenRec('device_tbl',$accessToken);
        //echo '<pre>'; print_r($info); die();
		$userID = $info->user_id;
        if($this->login_model->isValidAccessToken($accessToken)==0){
            //echo "Accesstoken check"; die();
            $res['message']     = "AccessToken is not valid.";
            $res['success'] = false;
        } else{
			    $usr_result = $this->login_model->getUserName($userID);
				//echo '<pre>'; print_r($usr_result);  //die();
				$adminID = 1;
				$admin_info = $this->login_model->getAdminInfo($adminID);
				//echo '<pre>'; print_r($admin_info);  die();
				$feedBackEmailID = $admin_info->feedback_email;

				$userDignosisAll = $this->login_model->user_dignosis($info->user_id);
				//echo '<pre>'; print_r($userDignosisAll);  die();
				if(count($userDignosisAll)>0){
				$diagnosisIds = [];
				$diagnosisTitles = [];
				foreach($userDignosisAll as $key=>$userDignosis){
					$diagnosisIds = $userDignosis['diagnosis_id'];
					if($diagnosisIds!=0){
						$getUserDignosisInfo = $this->login_model->getUserDignosisAddedInfo($diagnosisIds);
						//echo '<pre>'; print_r($getUserDignosisInfo);
						$UserDiganosisTitleFetch = '';
						$UserDiganosisTitleFetch.= $getUserDignosisInfo[0]['title'];
					}

				  }
			   }
				//echo $UserDiganosisTitleFetch; die();
				if(count($usr_result)>0){
					$userId = $usr_result->id;
					$userName = $usr_result->first_name;
					$userLName = $usr_result->last_name;
					$userEmail = $usr_result->email;
                    if($userEmail){
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
						$this->email->from('rakesh@isisdsn.net','Pathways');
						$this->email->to($feedBackEmailID);
						//$this->email->to('rakesh@radikal-labs.com');

						$this->email->subject('Feedback');
						$msg = '<html><body>';
						$msg .= '<h3 style="color:#f40;">Hi '.$userName.'!</h3>';
						$msg .= '<p>Email: '.$userEmail.'</p>
								<p>Subscription Type: </p>
								<p>Condition: '.$UserDiganosisTitleFetch.'</p>
								<p>Comment: '.$userComment.'</p>
								<p>Warm Regards <br> Pathways Team</p>';
						$msg .= '</body></html>';
						$this->email->message($msg);

						if (!$this->email->send()){
							$messge =$this->email->print_debugger();
						}else{
							 $res['success'] = true;
							 $messge = array('message' => 'Please check your email.');
						}
					}else{
						$messge = array('message' => 'Please try again.');
						$res['success'] = false;
					}
				}else{
					$messge = array('message' => 'Please enter correct username.');
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
