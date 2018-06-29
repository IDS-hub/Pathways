<?php
class Login_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function isValideEmail($data){
		$query = "SELECT id FROM user_tbl WHERE email = '".$data['email']."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			$row = $rs->row();
			$id = $row->id;
			return $id;
		}else{
			return 0;
		}
    }
    
       public function isValidAccessToken($accessToken){
        //echo $accessToken; die();
		$query = "SELECT id FROM device_tbl WHERE access_token = '".$accessToken."'";
        //echo $query; die();
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
            //echo "rows found"; die();
			$row = $rs->row();
			$id = $row->id;
			return $id;
		}else{
            //echo "Not found"; die();
			return 0;
		}
    }
    
	public function checkSessionExist($userID,$session_id){
		$query = "SELECT id FROM user_sessions WHERE user_id = '".$userID."' AND session_id='".$session_id."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			//echo "1"; die();
			$row = $rs->row();
			$id = $row->id;
			return $id;
		}else{
			//echo "2"; die();
			return 0;
		}
    }
	
	public function update_Session_iswatched($userID,$session_id) {
		$this->db->where('user_id',$userID);
		$this->db->where('session_id',$session_id);
		//$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		
		$data = array('isWatched'=>'1','updated'=>date('Y-m-d H:i:s'));
		$this->db->update('user_sessions', $data);
	}
	
	public function getUserdiagnosis($userID) {
		$this->db->select('*');
		$this->db->from('user_diagnosis_tbl');
		$this->db->where('user_id', $userID);
		$query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	
	public function lastSessionInfoFetch($userID) {
		$this->db->select('*');
		$this->db->from('user_sessions');
		$this->db->where('user_id', $userID);
		$this->db->order_by('session_id', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	
	
	public function getLastSessionIDValue($userID) {
		$this->db->select('*');
		$this->db->from('user_sessions');
		$this->db->where('user_id', $userID);
		$this->db->where('session_id<=', 62);
		$this->db->order_by('session_id', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	
	public function getlastIncreasePainSessionID($userID,$FirstPainIncreaseSessionID) {
		$this->db->select('*');
		$this->db->from('user_sessions');
		$this->db->where('user_id', $userID);
		$this->db->where('session_id', $FirstPainIncreaseSessionID);
		$this->db->order_by('session_id', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->result_array();
		return $res;
	}

	
	public function getlastDecreasePainSessionID($userID,$FirstPainDecreaseSessionID) {
		$this->db->select('*');
		$this->db->from('user_sessions');
		$this->db->where('user_id', $userID);
		$this->db->where('session_id', $FirstPainDecreaseSessionID);
		$this->db->order_by('session_id', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->result_array();
		return $res;
	}
    
	public function ftech_Pain_diagnsisInfo($Userdiagnosis_ID) {
		$this->db->select('*');
		$this->db->from('pain_diagnosis_tbl');
		//$this->db->where('diagnosis_id', $Userdiagnosis_ID);
		//$this->db->where('diagnosis_id > 0');
		
		$array = array('diagnosis_id'=>$Userdiagnosis_ID,'diagnosis_id >' => 0); 
		$this->db->where($array);
		$query = $this->db->get();
		$res = $query->row();
		return $res;
		
	}
	
	public function update_subscribe_user($userID,$subcriptionCode) {
		$this->db->where('id',$userID);
		if($subcriptionCode==1){
			//echo "OK11======";
			$date = strtotime(date('Y-m-d h:i:s'));
			$subscription_end_date = date('Y-m-d h:i:s',strtotime('+1 month',$date));
			$data = array('isSubscribed'=>'1', 'subscription_end_date'=>$subscription_end_date);
			$this->db->update('user_tbl', $data);
			return 1;
		} else if($subcriptionCode==2){
			//echo "OK2===";
			$date = strtotime(date('Y-m-d h:i:s'));
			$subscription_end_date = date('Y-m-d h:i:s',strtotime('+12 month',$date));
			$data = array('isSubscribed'=>'1', 'subscription_end_date'=>$subscription_end_date);
			$this->db->update('user_tbl', $data);
			return 1;
		} else if($subcriptionCode==3){
			//echo "OK3===";
			$date = strtotime(date('Y-m-d h:i:s'));
			$subscription_end_date = date('Y-m-d h:i:s',strtotime('+230 month',$date));
			//echo $subscription_end_date; die();
			$data = array('isSubscribed'=>'1', 'subscription_end_date'=>$subscription_end_date);
			$this->db->update('user_tbl', $data);
			return 1;
		} 
		
	}
	
	public function updateIsSubscribed($userID) {
		$this->db->where('id', $userID);
		$data = array('isSubscribed'=>'0');
		$this->db->update('user_tbl', $data);
		return "0";
	}
	
	public function user_pain_created_time($userID) {
		$this->db->select('*');
		$this->db->from('user_pains');
		$this->db->where('user_id', $userID);
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	
	
	public function GetUserStatisticsList($userID) {
		$this->db->select('*');
		$this->db->from('user_pains');
		$this->db->where('user_id', $userID);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(10,0);
		$query = $this->db->get();
		$res = $query->result_array();
		return $res;
	}
	
	
		public function user_pain_num_hit($userID) {
		$this->db->select('*');
		$this->db->from('user_pains');
		$this->db->where('user_id', $userID);
		//$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
		$this->db->where('pain_date',date('Y-m-d'));
		$query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	 
	 public function user_pain_num_hit_update($userID,$data, $painDateval) {
		 //echo $userID; 
		 //print_r($data); die();
		 $this->db->where('user_id', $userID);
		 $this->db->where('pain_date', $painDateval);
		 $final_data = array('pain_level'=>$data['pain_level'], 'num_hit'=>$data['num_hit']);
		 //print_r($final_data); die();
		 $this->db->update('user_pains', $final_data);
	 }
	
	

	public function fanfunc($id){
		$query = "SELECT * FROM fan_tbl WHERE created_by_user_id = '".$id."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return 0;
		}
    }

	public function get_full_details($table_name,$field_name,$condition,$shortorder,$searchvalue,$getvalue,$limit, $start){
		if($limit > 0){
			$this->db->limit($limit, $start);
		}

		if(count($shortorder) > 0){
			foreach($shortorder as $shortorder1){
				$this->db->order_by($shortorder1[0],$shortorder1[1]);
			}
		}

		if(count($searchvalue) > 0){
			foreach($searchvalue as $searchvalue1){
				$this->db->like($searchvalue1[0],$searchvalue1[1],'both');
			}
		}

		$fulldetails = $this->db->select($field_name)->get_where($table_name, $condition);
		//echo $this->db->last_query(); die();
		if($fulldetails->num_rows() > 0){
			if($getvalue!=0){
				$return=$fulldetails->result_object();
			}else{
				$return=$fulldetails->row();
			}
		}else{
			$return=array();
		}

		return $return;
	}


	public function deviceRecord($table_name,$field_name,$condition){
		//$sql = mysql_query("SELECT ".$field_name." FROM ".$table_name." WHERE id='".$condition."'");
		//$data = mysql_fetch_array($sql);
		//$return = $data['country_name'];
		$fulldetails = $this->db->select($field_name)->get_where($table_name, $condition);
		if($fulldetails->num_rows() > 0){

				$return=$fulldetails->row();
		}else{
			$return=array();
		}
	    return $return;
	}

	public function get_search_res($uid, $search_str = null,$limit = null, $limit_start = null){

		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->order_by("user_tbl.first_name", "asc");
		$this->db->group_start();
	        //$this->db->like('LOWER(first_name)', $search_str);
	        //$this->db->or_like('LOWER(last_name)',$search_str);
	        //$this->db->or_like('mob_no',$search_str);
                $this->db->where("CONCAT_WS(' ',first_name,last_name) LIKE '%".$search_str."%'");
		$this->db->group_end();
		$this->db->group_start();
		$this->db->where('is_active','1');
		$this->db->where('id !=', $uid);
		$this->db->group_end();

		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	        $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}
	public function getUserName($val){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$val);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}
	
	public function getFirstSession($SessionId) {
		$this->db->select('*');
		$this->db->from('session_tbl');
		$this->db->where('id', $SessionId);
	    $query = $this->db->get();
		$res = $query->row();
		return $res;
	}
	
	public function getFifthSessionInfo($fifthSessionID,$Userdiagnosis_ID) {
		$this->db->select('*');
		$this->db->from('pain_diagnosis_tbl');
		$this->db->where('session_id', $fifthSessionID);
		$this->db->where('diagnosis_id', $Userdiagnosis_ID);
	    $query = $this->db->get();
		$res = $query->result_array();
		return $res;
		
	}
	
	public function getUserSessionData($userID) {
		$this->db->select('*');
		$this->db->from('user_sessions');
		$this->db->where('user_id', $userID);
	    $query = $this->db->get();
		$res = $query->result_array();
		return $res;
	}

	public function numberofAveragePain($userID){
		$this->db->select('*');
		$this->db->from('userdaily_averagepain');
		$this->db->where('user_id', $userID);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}
	
	public function getLastConsiderationPointVal($userID){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id', $userID);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}
	
	public function updateLastConsidrationValueDate($userID,$LastConsideration_Value,$LastConsideration_Date){
		$this->db->where('id', $userID);
		$data = array('last_consideration_value'=>$LastConsideration_Value, 'last_consideration_date'=>$LastConsideration_Date);
		$this->db->update('user_tbl', $data);
		
	}
	
	
	public function lastTwoAverageRatePain($userID){
		$this->db->select('*');
		$this->db->from('userdaily_averagepain');
		$this->db->where('user_id', $userID);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(2,0);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	
	public function getAverageRatePain_LastConsidearionDate($userID,$getUserLastConsiderationDate_value){
		$this->db->select('*');
		$this->db->from('userdaily_averagepain');
		$this->db->where('user_id', $userID);
		$this->db->where('created >', $getUserLastConsiderationDate_value);
	
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}
	
	
	public function lastTwoAverageRatePainNEW($userID,$getUserLastConsiderationDate_value){
		$this->db->select('*');
		$this->db->from('userdaily_averagepain');
		$this->db->where('user_id', $userID);
		$this->db->where('created >', $getUserLastConsiderationDate_value);
	
		$this->db->order_by('created', 'DESC');
		$this->db->limit(1,0);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	public function updateProfile($table_name,$id,$data = array()){
		$this->db->where('id',$id);
 		if($this->db->update($table_name,$data)){
			return 1;
		}else{
			return 0;
		}
	}

	public function userRec($table_name,$id){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}
        
        public function useraccessTokenRec($table_name,$accessToken){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('access_token',$accessToken);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}
        
        public function getSessionInfo($table_name) {
            $this->db->select('*');
            $this->db->from($table_name);
            $this->db->order_by("id", "asc");
            $query = $this->db->get();
            $res = $query->result_array();
	    return $res;
        }
		
		
		public function getSpecificSessionInfo($SessionID_value) {
            $this->db->select('*');
            $this->db->from('session_tbl');
			$this->db->where('id', $SessionID_value);
            $query = $this->db->get();
            $res = $query->result_array();
	    return $res;
        }
        
		
		public function NumberOfUnwatchedSEssion($userID) {
            $this->db->select('*');
            $this->db->from('user_sessions');
			$this->db->where('user_id', $userID);
			$this->db->where('isWatched', '0');
            $query = $this->db->get();
            $res = $query->result_array();
	    return $res;
        }
        
         public function getAllDiagnosisInfo($table_name) {
            $this->db->select('*');
            $this->db->from($table_name);
            $this->db->order_by("id", "asc");
            $query = $this->db->get();
            $res = $query->result_array();
	    return $res;
        }


	public function tokenAccess(){
		$headers=array();
		foreach (getallheaders() as $name => $value) {
		    $headers[$name] = $value;
		}
		//print_r($headers);
		$token = $headers['token'];
		$result = $this->get_full_details('device_tbl','*',array('access_token' => $token),array(),array(),0,0,0);
		return $result;
	}

	public function get_login($usr, $pwd){
		$this->db->where('username', $usr);
		$this->db->where('password', $pwd);
		$this->db->where('status', '1');
		$query = $this->db->get('admin_tbl');
		//echo $this->db->last_query();die();
		return $query->row();
	}

	public function get_forget_password($val){
		$this->db->select('*');
		$this->db->from('admin_tbl');
		$this->db->where('username',$val);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}
	
	public function get_forget_password_user($val){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('email',$val);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}

	

	public function record($table_name){
		$this->db->select('*');
		$this->db->where('status','0');
		$this->db->from($table_name);
		//$this->db->order_by("id", "desc");
                $this->db->order_by("coin_amt", "asc");
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}


	public function chk_gogole_user($table_name,$gid){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('google_id',$gid);
		$q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	

	public function ageCal($Bday){
		$today = new DateTime();
		$diff = $today->diff(new DateTime($Bday));
		return (($diff->y)>0)?$diff->y:0;
	}

	

	public function webLogDel($uid){
		$this->db->select('*');
		$this->db->from('qr_tbl');
		$this->db->where('user_id', $uid);
		$q = $this->db->get();
		//echo $this->db->last_query();die();
		if($q->num_rows()>0){
			$ip = $q->row('ip');
			$data=array('user_id'=>0);
			$this->db->where('ip',$ip);
			$this->db->update('qr_tbl',$data);
			///echo $this->db->last_query();die();
		}
	}
	public function idInfo($uid){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id', $uid);
		$q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->result_array();
		return $res;
	}

	

	public function get_user_pass($email){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('email',$email);
		$this->db->limit(1,0);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		//$password = $query->row();
		return $query->row();
	}

	

	public function varify_check($country_code = null, $mob_no = null, $activation_code = null){
		$this->db->select('*');
		$this->db->from('twilio_tbl');
		$this->db->group_start();
		$this->db->where("mob_no =", $mob_no);
		$this->db->where("country_code =", $country_code);
		$this->db->where("activation_code =", $activation_code);
		$this->db->group_end();
	        $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->row();
		return $res;
	}

        public function forgetpassword_varify_check($mob_no = null, $activation_code = null){
		$this->db->select('*');
		$this->db->from('twilio_tbl');
		$this->db->group_start();
		$this->db->where("mob_no =", $mob_no);
		$this->db->where("activation_code =", $activation_code);
		$this->db->group_end();
	        $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->row();
		return $res;
	}

        public function forgetpassword_varify_check_twilio($mob_no = null){
		$this->db->select('*');
		$this->db->from('twilio_tbl');
		$this->db->group_start();
		$this->db->where("mob_no =", $mob_no);
		$this->db->group_end();
	        $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->row();
		return $res;
	}

        public function fetch_country_code($mob_no = null){
		$this->db->select('*');
		$this->db->from('twilio_tbl');
		$this->db->group_start();
		$this->db->where("mob_no =", $mob_no);
		$this->db->group_end();
	        $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->row();
		return $res;
	}


        public function forgotpassword_new_update($email = null, $newpassword = null) {
                $data=array('password'=>$newpassword);
                //echo '<pre>'; print_r($data); die();
                $this->db->where('email',$email);
                $this->db->update('user_tbl',$data);
        }


        public function forgotpassword_activation_code_update($mob_no = null, $activation_code = null) {
                $data=array('activation_code'=>$activation_code);
                //echo '<pre>'; print_r($data); die();
                $this->db->where('mob_no',$mob_no);
                $this->db->update('twilio_tbl',$data);
        }

	public function varify_twilio_no($country_code=null,$mob_no = null){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->group_start();
		$this->db->where("country_code =", $country_code);
		$this->db->where("mob_no =", $mob_no);
		$this->db->group_end();
                $rs = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $rs->row();
		return $res;
	}



	public function rand_reset($id,$rnd){
		$data=array('varify'=>$rnd);
		$this->db->where('id',$id);
		if($this->db->update('admin_tbl',$data)){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function rand_reset_user($id,$rnd){
		$data=array('varify'=>$rnd);
		$this->db->where('id',$id);
		if($this->db->update('user_tbl',$data)){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	function save_verification($data)
	{
		$savedata = array(
			'id' => $data['id'],
			'verification_code' => $data['verification_code']
		);
		
		$this->db->where('id', $data['id']);
		$return=$this->db->update('user_tbl', $savedata);
		
		return $return;
	}
	
	
	function check_verification($veri_code)
	{
		
		$detail_count_query = "SELECT * FROM `user_tbl` WHERE `verification_code`='".$veri_code."'";
		$rs = $this->db->query($detail_count_query);
		if ($rs->num_rows() >0 )
		{
			$savedata = array(
				'verification_code' => '',
				'is_active' => "1"
			);
		
			$this->db->where('verification_code', $veri_code);
			$return=$this->db->update('user_tbl', $savedata);
		}
		else
		{
			$return='';
		}
		
		return $return;
	}
	
	
	
	public function password_chk($pass,$hidnum){
		$this->db->select('*');
		$this->db->from('admin_tbl');
		$this->db->where('varify',$hidnum);
	    $rs = $this->db->get();
		//echo $this->db->last_query();die();
		$id = $rs->row('id');
		if($id!=''){
			//$data=array('password'=>$pass);
			$data=array('password'=>$pass,'varify'=>'');
			$this->db->where('id',$id);
			if($this->db->update('admin_tbl',$data)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	
		public function password_chk_user($pass,$hidnum){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('varify',$hidnum);
	    $rs = $this->db->get();
		//echo $this->db->last_query();die();
		$id = $rs->row('id');
		if($id!=''){
			//$data=array('password'=>$pass);
			$data=array('password'=>$pass,'varify'=>'');
			$this->db->where('id',$id);
			if($this->db->update('user_tbl',$data)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	public function checkRand($rnd){
		$this->db->select('id');
		$this->db->from('admin_tbl');
		$this->db->where('varify',$rnd);
	    $rs = $this->db->get();
		return $rs->row('id');
	}

	

	public function user_rec($uid,$search_str = null,$limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from('user_tbl');
		if($search_str!=''){
			$this->db->group_start();
		    $this->db->like('LOWER(first_name)', trim($search_str), 'both');
		    $this->db->or_like('LOWER(last_name)',trim($search_str), 'both');
			$this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($search_str), 'both');
			$this->db->group_end();
		}
		$this->db->group_start();
		$this->db->where('id!=',$uid);
		$this->db->where('is_active =','1');
		$this->db->group_end();
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
		$this->db->order_by("id", "desc");
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}
	
	
	public function user_dignosis($id){
		$this->db->select('*');
		$this->db->where('user_id',$id);
		$this->db->from(user_diagnosis_tbl);
		$q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}
	
	public function isUserPresent($userID){                
		$query = "SELECT * FROM user_diagnosis_tbl WHERE user_id = '".$userID."'";                
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){			
			return 1;
		}else{                        
			return 0;
		}
    }
	

	
}
?>
