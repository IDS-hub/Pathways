<?php
class Login_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}

	public function isValideMobile($data){
		$query = "SELECT id FROM user_tbl WHERE mob_no = '".$data['mob_no']."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			$row = $rs->row();
			$id = $row->id;
			return $id;
		}else{
			return 0;
		}
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
	    $this->db->like('LOWER(first_name)', $search_str);
	    $this->db->or_like('LOWER(last_name)',$search_str);
	    $this->db->or_like('mob_no',$search_str);
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

	public function get_currency(){
		$this->db->select('*');
		$this->db->from('money_tbl');
		$this->db->order_by("coins", "asc");
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	public function folrec($table_name,$condition,$limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('follower_id',$condition);
		//$this->db->select('*')->get_where($table_name, $condition);
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function unFollow($table_name,$condition){
		$tmpdetails = $this->db->get_where($table_name, $condition);
		if($tmpdetails->num_rows() > 0){
			$this->db->where($condition);
			$return = $this->db->delete($table_name);
		}else{
			$return=0;
		}
	    return $return;
	}

	public function streamrec($table_name,$featured,$limit = null, $limit_start = null,$uid){
		//$conditions['stream_url']!='';
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->group_start();
		if($featured==1){
			$conditions['is_featured']='1';
			$this->db->where($conditions);
		}
		//$this->db->where('stream_url !=""');
		$this->db->where('broadcast_id !=""');
		$this->db->where('created_by_user_id !=', $uid);
		$this->db->group_end();
		$this->db->order_by("id", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
		//print_r($res); die();
	    return $res;
	}

	public function userStreamInfo($table_name,$condition){
		$fulldetails = $this->db->select($field_name)
						->get_where($table_name, $condition);
		if($fulldetails->num_rows() > 0){
				$return=$fulldetails->row();
		}else{
			$return=array();
		}
	    return $return;
	}

	public function fanrec($table_name,$fan_id,$limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where("fan_id='$fan_id'");
		$this->db->order_by("id", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function fanInfo($table_name,$condition,$limit = null, $limit_start = null){
		$fulldetails = $this->db->select($field_name)
						->get_where($table_name, $condition);
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
		if($fulldetails->num_rows() > 0){
				$return=$fulldetails->row();
		}else{
			$return=array();
		}
	    return $return;
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

	public function get_stream_rec($val){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id',$val);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}

	public function stream_rec_max2($sessId,$roll){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->where('tokbox_session_id',$sessId);
		$this->db->where('role_type',$roll);
		$query = $this->db->get();
		$cnt = $query->num_rows();
	    return $cnt;
	}

	public function get_participant($stream_id,$userId){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		//$this->db->where('tokbox_session_id',$sessId);
		$this->db->where('stream_id',$stream_id);
		$this->db->where('created_by_user_id',$userId);
		$query = $this->db->get();
		$cnt = $query->num_rows();
		//echo $this->db->last_query();die();
	    return $cnt;
	}

	public function get_participants_stream_rec($sid,$uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		//$cnt = 0;
		$cnt = $query->row();
	    return $cnt;
	}

	public function update_status_participants_stream_rec($sid,$uid,$stt){
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		//$this->db->delete('stream_participants_tbl');
		$data=array('is_active'=>$stt);
		$this->db->update('stream_participants_tbl',$data);
	}
	public function rem_view_cnt($sid){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id',$sid);
		$query = $this->db->get();
		$res = $query->row('view_cnt');
		if($res>0){
			$res=$res-1;
			$data=array('view_cnt'=>$res);
			$this->db->where('id',$sid);
			$this->db->update('stream_tbl',$data);
		}
	}

	public function get_stream_view_cnt($val){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id',$val);
		$query = $this->db->get();
		$res = $query->row('view_cnt');
	    return $res;
	}
	public function update_stream_view_cnt($id,$plas){
		$data=array('view_cnt'=>$plas);
		$this->db->where('id',$id);
 		$this->db->update('stream_tbl',$data);
		//echo $this->db->last_query();die();
	}


	public function updateStream($table_name,$id,$data = array()){
		$this->db->where('id',$id);
 		if($this->db->update($table_name,$data)){
			return 1;
		}else{
			return 0;
		}
	}

	public function record($table_name){
		$this->db->select('*');
		$this->db->where('status','0');
		$this->db->from($table_name);
		$this->db->order_by("id", "desc");
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function gift_amt($table_name,$gift_id){
		$this->db->select('coin_amt');
		$this->db->from($table_name);
		$this->db->where('id',$gift_id);
		$query = $this->db->get();
		$res = $query->row('coin_amt');
	    return $res;
	}

	public function gift_dtl($table_name,$gift_id){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$gift_id);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	public function chk_balance($table_name,$uid,$amt){
		$this->db->select('coins_earned');
		$this->db->from($table_name);
		$this->db->where('id',$uid);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$coins_earned = $query->row('coins_earned');
		$res = 0;
		if(isset($coins_earned) && $coins_earned>=$amt){
			$res = 1;
		}
	    return $res;
	}

	public function add_balance($table_name,$uid,$sid,$amt){
		if($uid){
			$this->db->select('*');
			$this->db->from($table_name);
			$this->db->where('id',$uid);
			$query = $this->db->get();
			$coins_earned = $query->row('coins_earned');
			$coins_earned = $coins_earned - $amt;
			$coins_spent = $query->row('coins_spent');
			$coins_spent = $coins_spent + $amt;
			//$data=array('coins_earned'=>$coins_earned,'coins_spent'=>$coins_spent);
			$data=array('coins_spent'=>$coins_spent);
			$this->db->where('id',$uid);
			$this->db->update($table_name,$data);
		}
		if($sid){
			$this->db->select('coins_earned');
			$this->db->from($table_name);
			$this->db->where('id',$sid);
			$query = $this->db->get();
			$coins_earned = $query->row('coins_earned');
			$coins_earned = $coins_earned + $amt;
			$data=array('coins_earned'=>$coins_earned);
			$this->db->where('id',$sid);
			$this->db->update($table_name,$data);
		}
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

	public function rec_follewer_id($uid,$limit = null, $limit_start = null){
		$this->db->select('s.*');
	    $this->db->from('stream_tbl as s');
	    $this->db->join('follower_tbl as f','f.follower_id=s.created_by_user_id');
		$this->db->group_start();
		$this->db->where('s.stream_url !=""');
		$this->db->where("f.created_by_user_id = '$uid'");
		$this->db->where('s.created_by_user_id !=', $uid);
		$this->db->group_end();
		$this->db->order_by("s.id", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	public function chk_follower($uid,$fid){
		$this->db->select('*');
		$this->db->from('follower_tbl');
		$this->db->group_start();
		$this->db->where('created_by_user_id',$uid);
		$this->db->where('follower_id',$fid);
		$this->db->group_end();
		$query = $this->db->get();
		$flag = $query->row('id');
		$res=0;
		if(isset($flag)){
			$res = 1;
		}
	    return $res;
	}
	public function chk_fan($uid,$fid){
		$this->db->select('*');
		$this->db->from('fan_tbl');
		$this->db->group_start();
		$this->db->where('created_by_user_id',$uid);
		$this->db->where('fan_id',$fid);
		$this->db->group_end();
		$query = $this->db->get();
		$flag = $query->row('id');
		$res=0;
		if(isset($flag)){
			$res = 1;
		}
	    return $res;
	}
	public function set_user_coins_earned($uid,$amt){
		$data=array('coins_earned'=>$amt);
		$this->db->where('id',$uid);
		$this->db->update('user_tbl',$data);
	}
	public function parchase_record($uid,$limit = null, $limit_start = null){
		$this->db->select('*');
	    $this->db->from('purchase_tbl');
		$this->db->where("user_id = '$uid'");
		$this->db->order_by("id", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}
	public function parchase_list($limit = null, $limit_start = null){
		$this->db->select('*');
	    $this->db->from('purchase_list_tbl');
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}
	public function purchase_amt($table_name,$coin_id){
		$this->db->select('amount');
		$this->db->from($table_name);
		$this->db->where('id',$coin_id);
		$query = $this->db->get();
		$res = $query->row('amount');
	    return $res;
	}
	public function purchase_coin($table_name,$coin_id){
		$this->db->select('coin');
		$this->db->from($table_name);
		$this->db->where('id',$coin_id);
		$query = $this->db->get();
		$res = $query->row('coin');
	    return $res;
	}
	public function find_purchase_id($purchase_identifier,$type=null){
		$this->db->select('*');
		$this->db->from('purchase_list_tbl');
		if($type=='ios'){
			$this->db->where('demo_ios_identifier',$purchase_identifier);
		}else if($type=='android'){
			$this->db->where('demo_android_identifier',$purchase_identifier);
		}else{
			$this->db->where('identifier',$purchase_identifier);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $query->row('id');
	    return $res;
	}

	public function ageCal($Bday){
		$today = new DateTime();
		$diff = $today->diff(new DateTime($Bday));
		return (($diff->y)>0)?$diff->y:0;
	}

	public function coin_rank($uid,$limit = null, $limit_start = null,$type){
		$this->db->select('*');
	    $this->db->from('user_tbl');
		if($type==1){
			$this->db->order_by("coins_earned", "desc");
		}else{
			$this->db->order_by("coins_spent", "desc");
		}
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $query = $this->db->get();
		$res = $query->result_array();
		$rest= array();
		$i=0;
		$p=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['user_code'] = ($rec['user_code']!='')?$rec['user_code']:'';
			$rest[$i]['first_name'] = $rec['first_name'];
			$rest[$i]['last_name'] = $rec['last_name'];
			$rest[$i]['mob_no'] = $rec['mob_no'];
			if($type==1){
				$rest[$i]['coins_earned'] = $rec['coins_earned'];
			}else{
				$rest[$i]['coins_spent'] = $rec['coins_spent'];
			}
			$rest[$i]['profile_image'] = ($rec['profile_image']!='')?$rec['profile_image']:'';
			$rest[$i]['cover_image'] = ($rec['cover_image']!='')?$rec['cover_image']:'';
			$rest[$i]['rank'] = ($limit_start==0 && $p<5)?'1':'0';
			$rest[$i]['flag'] = ($rec['id']==$uid)?'1':'0';
			$rest[$i]['following'] = $this->followingfunc('follower_tbl',$rec['id']);
			$rest[$i]['follower'] = $this->followerfunc('follower_tbl',$rec['id']);
			$ag = strlen((string)$this->ageCal($rec['dob']));
			$rest[$i]['age'] = ($ag>2)?0:$this->ageCal($rec['dob']);
			$rest[$i]['gender'] = $rec['gender'];
			$rest[$i]['no_of_fan'] = (string)$this->fanfunc($rec['id']);
			$totalAmount = $rec['coins_earned'] - ($rec['coins_spent'] + $rec['coins_withdrawn']);
			$rest[$i]['level'] = $this->levelfunc('level_tbl',$totalAmount);
			$rest[$i]['location'] = ($rec['location']!='')?$rec['location']:'';

			$this->db->select('*');
		    $this->db->from('follower_tbl');
			$this->db->group_start();
			$this->db->where('created_by_user_id',$uid);
			$this->db->where('follower_id',$rec['id']);
			$this->db->group_end();
			$query2 = $this->db->get();
			$flag2 = $query2->row('id');
			$tag='0';
			if(isset($flag2)){
				$tag='1';
			}
			$rest[$i]['is_follow'] = $tag;

			$i++;$p++;
		}
	    return $rest;
	}

	public function followingfunc($table_name,$condition){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('created_by_user_id',$condition);
	    $rs = $this->db->get();
		if ($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return 0;
		}
	}
	public function followerfunc($table_name,$condition){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('follower_id',$condition);
	    $rs = $this->db->get();
		if ($rs->num_rows() >0 ){
			return $rs->num_rows();
		}else{
			return 0;
		}
	}

	public function get_user_earned_update($uid,$amt_earn,$amt_witd){
		$data=array('coins_earned'=>$amt_earn,'coins_withdrawn'=>$amt_witd);
		$this->db->where('id',$uid);
		$this->db->update('user_tbl',$data);
	}

	public function get_user_earned_chk($uid,$coin){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
	    $rs = $this->db->get();
		$coins_earned = $rs->row('coins_earned');
		$coins_withdrawn = $rs->row('coins_withdrawn');
		$totalcoin = $coin + $coins_withdrawn;
		if($coins_earned>$totalcoin){
			return 0;
		}else{
			return 1;
		}
	}

	public function updatePoint($uid,$point){
		$this->db->select('coins_earned');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
	    $rs = $this->db->get();
		//echo $this->db->last_query();die();
		$pnt = $rs->row('coins_earned');
		if($point==1){
			$pnt = $pnt + 1;
		}else if($point==2){
			$pnt = $pnt + 1;
		}else if($point==4){
			$pnt = $pnt + 5;
		}else if($point==3){
			$pnt = $pnt + 5;
		}
		$data=array('coins_earned'=>$pnt);
		$this->db->where('id',$uid);
		$this->db->update('user_tbl',$data);
	}

	public function get_stream_viewer($uid, $stream_id, $limit = null, $limit_start = null){
		$this->db->distinct();
		//$this->db->select('u.id,u.first_name,u.last_name,u.profile_image');
		$this->db->select('u.*');
		$this->db->from('user_tbl as u');
		$this->db->join('stream_participants_tbl as s','s.created_by_user_id=u.id');
		//$this->db->join('stream_tbl as st','st.created_by_user_id=u.id');
		$this->db->order_by("s.id", "asc");

		$this->db->group_start();
		$this->db->where('s.created_by_user_id !=', $uid);
		$this->db->where('s.stream_id =', $stream_id);
		//$this->db->where('s.stream_id = st.id');
		$this->db->where('s.role_type != "publisher"');
		$this->db->where('s.is_active != 1');
		$this->db->group_end();

		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function get_stream_viewer_participant($uid, $stream_id, $limit = null, $limit_start = null){
		$this->db->distinct();
		$this->db->select('u.*');
		$this->db->from('user_tbl as u');
		$this->db->join('stream_participants_tbl as s','s.created_by_user_id=u.id');
		$this->db->join('stream_tbl as v','v.id=s.stream_id');
		$this->db->order_by("s.id", "asc");

		$this->db->group_start();
		$this->db->where('s.created_by_user_id !=', $uid);
		$this->db->where('s.stream_id =', $stream_id);
		$this->db->where('s.role_type != "subscriber"');
		$this->db->where('s.is_active != 1');
		$this->db->where('s.created_by_user_id !=v.created_by_user_id');
		$this->db->group_end();

		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	/*$this->db->select('s.*');
	$this->db->from('stream_tbl as s');
	$this->db->join('follower_tbl as f','f.follower_id=s.created_by_user_id');
	$this->db->where("f.created_by_user_id = '$uid'");
	$this->db->order_by("s.id", "desc");
	$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
	$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	$query = $this->db->get();
	$res = $query->result_array();
	return $res;*/

	public function levelfunc($table_name,$val){
		if($val>0){
			$this->db->select('*');
			$this->db->from($table_name);
			$this->db->where($val." BETWEEN start_range AND end_range");
		    $rs = $this->db->get();
			if($rs->num_rows()>0){
				return $rs->row('level_no');
			}else{
				return "0";
			}
		}else{
			return "0";
		}

	}

	public function chat_limit($stream_id){
		$this->db->query("DELETE FROM `chat_tbl` WHERE stream_id =".$stream_id." and id NOT IN (SELECT id FROM (SELECT id FROM `chat_tbl` where stream_id =".$stream_id." ORDER BY id DESC LIMIT 4) foo)");
	}

	public function add_chat_message($stream_id,$from,$to,$message,$time){
		$this->chat_limit($stream_id);
		//echo $this->db->last_query();die();
		$this->db->insert("chat_tbl",array('stream_id' => $stream_id,'msg_from' => $from,'msg_to' => $to,'message' => $message,'sent' => $time));
		return $this->db->insert_id();
	}
	public function add_chat_gift($stream_id,$from,$to,$gift,$time){
		//$this->db->insert("chat_tbl",array('tokbox_session_id' => $tokbox_session_id,'msg_from' => $from,'msg_to' => $to,'gift_id' => $gift,'sent' => $time));
		//return $this->db->insert_id();
		$this->chat_limit($stream_id);
		$exp = explode(',',$gift);
		$total_amt = 0;
		if($exp[0]!='' && count($exp)>0){
			foreach ($exp as $val) {
				//$total_amt += $this->login_model->gift_amt('gift_tbl',$val);
				$this->db->insert("chat_tbl",array('stream_id' => $stream_id,'msg_from' => $from,'msg_to' => $to,'gift_id' => $val,'sent' => $time));
			}
		}
	}
	public function chatUserId($id){
		$this->db->select('first_name,last_name');
		$this->db->from('user_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$name = $q->row('first_name').' '.$q->row('last_name');
		return $name;
	}
	public function chatGiftImg($id){
		$this->db->select('gift_img');
		$this->db->from('gift_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$name = $q->row('gift_img');
		return $name;
	}

	public function chat_history($stream_id = null, $time = null){
		$this->db->select('*');
		$this->db->from('chat_tbl');
		//$this->db->where_in('msg_from ', array($from,$to));
		//$this->db->where_in('msg_to ', array($from,$to));
		$this->db->order_by("sent", "asc");
		$this->db->group_start();
		//$this->db->where("tokbox_session_id =", $tokbox_session_id);
		$this->db->where("stream_id =", $stream_id);
		$this->db->where("sent >=", $time);  //2017-08-04 15:35:59
		$this->db->group_end();

	    $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			//print_r($rec); die();
			//$rest[$i]['id'] = $rec['id'];
			$rest[$i]['sent'] = $rec['sent'];
			$rest[$i]['from'] = $this->chatUserId($rec['msg_from']);
			//$rest[$i]['to'] = $this->chatUserId($rec['to']);
			$rest[$i]['type'] = ($rec['gift_id']!=0)?'gift':'msg';
			$rest[$i]['message'] = ($rec['gift_id']!=0)?$this->chatGiftImg($rec['gift_id']):$rec['message'];
			$i++;
		}
	    return $rest;
	}

	public function chatCreateUserId($stream_id){
		$this->db->select('created_by_user_id');
		$this->db->from('stream_tbl');
		$this->db->where('id =', $stream_id);
		$q = $this->db->get();
		//echo $this->db->last_query();die();
		$uid = $q->row('created_by_user_id');
		return $uid;
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

	public function msg_notify($dateTime,$limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from('notification_tbl');
		//$this->db->where('status', '1');
		$this->db->where('datetime >=', $dateTime);
		$this->db->order_by("datetime", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			//print_r($rec); die();
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['title'] = $rec['title'];
			$rest[$i]['message'] = $rec['message'];
			$rest[$i]['date'] = date("d M, Y", strtotime($rec['datetime']));
			$rest[$i]['time'] = date("H:i:s", strtotime($rec['datetime']));
			$i++;
		}
	    return $rest;
	}

	public function banner_list($limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from('banner_tbl');
		$this->db->where('status', '0');
		$this->db->order_by("id", "desc");
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $q->result_array();
	    return $res;
	}


	public function stream_end_detl($sid=null){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id',$sid);
		$query = $this->db->get();
		$res = $query->row();
		$created = strtotime($res->created);
		$updated = strtotime($res->updated);
		$temp  = abs($updated - $created);

		$rest['time'] = gmdate("H:i:s",$temp);
		$rest['stream_viewer'] = $res->view_cnt;
		$rest['fan_cnt'] = $res->fan_cnt;

		$this->db->select('*');
		$this->db->from('chat_tbl');
		$this->db->where('stream_id',$sid);
		$query2 = $this->db->get();
		//echo $this->db->last_query();die();
		$res2 = $query2->result_array();
		$tot=0;
		foreach($res2 as $rec2){
			$gid = $rec2['gift_id'];
			if($gid!=0){
				$this->db->select('*');
				$this->db->from('gift_tbl');
				$this->db->where('id',$gid);
				$query3 = $this->db->get();
				$tot += $query3->row('coin_amt');
			}
		}
		$rest['stream_coin_earn'] = $tot;
	    return $rest;
	}

	public function total_viewr($uid){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('created_by_user_id',$uid);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $query->result_array();
		$tot=0;
		foreach($res as $rec){
			$tot += $rec['view_cnt'];
		}
		return $tot;
	}

	public function streamFollerDevice($uid){
		//$this->db->distinct();
		$this->db->select('d.device_type,d.device_token');
		$this->db->from('device_tbl as d');
		$this->db->join('follower_tbl as f','f.follower_id=d.user_id');
		$this->db->group_start();
		$this->db->where('f.created_by_user_id =', $uid);
		$this->db->where('d.device_type !=""');
		$this->db->group_end();
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $query->result_array();
	    return $res;
	}

	public function chk_like($uid,$send_id,$stream_id){
		$this->db->select('*');
		$this->db->from('like_tbl');
		$this->db->group_start();
		$this->db->where('user_id',$uid);
		$this->db->where('send_id',$send_id);
		$this->db->where('stream_id',$stream_id);
		$this->db->group_end();
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		return $query->num_rows();
	}

	public function count_stream_like($uid,$id){
		$this->db->select('*');
		$this->db->from('like_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$id);
		$this->db->group_end();
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$tot= $cnt = 0;
		$cnt = $query->num_rows();
		if($cnt>0){
			$cnt = (int)($cnt / 10);
		}
		$tot= $cnt * 2;
		if($tot!=0){
			$this->db->select('coins_earned');
			$this->db->from('user_tbl');
			$this->db->where('id',$uid);
		    $rs = $this->db->get();
			$pnt = $rs->row('coins_earned');
			$pnt = $pnt + $tot;
			$data=array('coins_earned'=>$pnt);
			$this->db->where('id',$uid);
			$this->db->update('user_tbl',$data);
		}
	}

	function stream_like_yes_no($uid,$sid){
		$this->db->select('*');
		$this->db->from('like_tbl');
		$this->db->group_start();
		$this->db->where('user_id',$uid);
		//$this->db->where('send_id',$sid);
		$this->db->where('stream_id',$sid);
		$this->db->group_end();
		$query = $this->db->get();
		$cnt = 0;
		$cnt = $query->num_rows();
		if($cnt>0){
			$cnt = 1;
		}
		return $cnt;
	}

	public function update_stream_fan($sid){
		$this->db->select('fan_cnt');
		$this->db->from('stream_tbl');
		$this->db->where('id',$sid);
		$query = $this->db->get();
		$pnt = $query->row('fan_cnt');
		$pnt = $pnt + 1;
		$data=array('fan_cnt'=>$pnt);
		$this->db->where('id',$sid);
		$this->db->update('stream_tbl',$data);
	}

	function chk_participate_stream($sid,$uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		$query = $this->db->get();
		$cnt = $query->num_rows();
		return $cnt;
	}

	public function stream_participate_publisher($uid){
		// $this->db->select('*');
		// $this->db->from('stream_participants_tbl');
		// $this->db->where('tokbox_session_id',$sessId);
		// $this->db->where('role_type',$roll);
		// $query = $this->db->get();
		// $cnt = $query->num_rows();
	    // return $cnt;
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		//$this->db->join('follower_tbl as f','f.follower_id=d.user_id');
		//$this->db->group_start();
		$this->db->where('created_by_user_id =', $uid);
		//$this->db->group_end();
		$this->db->order_by("id", "desc");
		$this->db->limit(1,0);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		//$res = $query->row();
		$stp_id = $query->row('id');
		$this->db->where('id',$stp_id);
		$data=array('role_type'=>'publisher');
		$this->db->update('stream_participants_tbl',$data);

	    //return $res;
	}

	public function update_participants_stream_rec($sid,$uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		//$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		$stp_id = $query->row('id');
		$this->db->where('id',$stp_id);
		//$data=array('role_type'=>'subscriber');
		$data=array('role_type'=>'subscriber','is_active'=>'1');
		$this->db->update('stream_participants_tbl',$data);
	}

	public function update_participants_status($sid,$uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		//$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		$stp_id = $query->row('id');
		$this->db->where('id',$stp_id);
		//$data=array('role_type'=>'subscriber');
		$data=array('role_type'=>'publisher','is_active'=>'1');
		$this->db->update('stream_participants_tbl',$data);
	}

	public function count_stream_participate_publisher($uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->where('created_by_user_id',$uid);
		$this->db->order_by("id", "desc");
		$this->db->limit(1,0);
		$query = $this->db->get();
		$strm_id = $query->row('stream_id');

		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->where('stream_id',$strm_id);
		$this->db->where('role_type','publisher');
		$query1 = $this->db->get();
		$cnt = $query1->num_rows();
		return $cnt;
	}

	public function get_user_pass($mob){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('mob_no',$mob);
		$this->db->limit(1,0);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		//$password = $query->row();
		return $query->row();
	}

	public function count_stream_publisher($sid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('role_type','publisher');
		$this->db->group_end();
		$query = $this->db->get();
		$cnt = $query->num_rows();
		return $cnt;
	}

	public function subscriber_to_publisher($sid,$uid){
		$this->db->select('*');
		$this->db->from('stream_participants_tbl');
		$this->db->group_start();
		$this->db->where('stream_id',$sid);
		$this->db->where('created_by_user_id',$uid);
		$this->db->group_end();
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get();
		$stp_id = $query->row('id');
		$this->db->where('id',$stp_id);
		$data=array('role_type'=>'publisher','is_active'=>'0');
		$this->db->update('stream_participants_tbl',$data);
	}

	public function stream_like($stream_id = null, $time = null){
		$this->db->select('*');
		$this->db->from('like_tbl');
		$this->db->group_start();
		$this->db->where("stream_id =", $stream_id);
		$this->db->where("created >=", $time);  //2017-08-04 15:35:59
		$this->db->group_end();
	    $q = $this->db->get();
		$cnt = $q->num_rows();
		return $cnt;
	}

	public function chkPoint($uid){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
	    $rs = $this->db->get();
		//echo $this->db->last_query();die();
		// $point =  $rs->row('coins_earned');
		// $chk=0;
		// if($point>100){
		// 	$chk = 1;
		// }else{
		// 	$chk = 0;
		// }
		// return $chk;
		$coins_earned = $rs->row('coins_earned');
		$coins_spent = $rs->row('coins_spent');
		$coins_withdrawn = $rs->row('coins_withdrawn');
		$tot = $coins_earned - ($coins_spent + $coins_withdrawn+100);
		//$chk=0;
		if($tot>0){
			return 1;
		}else{
			return 0;
		}
	}

	public function count_stream_fan($sid){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id',$sid);
		$query = $this->db->get();
		$cnt = $query->row('fan_cnt');
		return $cnt;
	}

	public function reduceCoinEarn($uid){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
	    $rs = $this->db->get();
		//echo $this->db->last_query();die();
		$coins_spent = $rs->row('coins_spent');
		$coins_spent = $coins_spent + 100;
		$data=array('coins_spent'=>$coins_spent);
		$this->db->where('id',$uid);
		$this->db->update('user_tbl',$data);
	}
	public function coin_duduction($uid){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
	    $rs = $this->db->get();
		$coins_earned = $rs->row('coins_earned');
		$coins_spent = $rs->row('coins_spent');
		$coins_withdrawn = $rs->row('coins_withdrawn');
		$tot = $coins_earned - ($coins_spent + $coins_withdrawn+10);
		if($tot>0){
			$coins_spent = $coins_spent + 10;
			$data=array('coins_spent'=>$coins_spent);
			$this->db->where('id',$uid);
			$this->db->update('user_tbl',$data);
			return 0;
		}else{
			return 1;
		}
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

	public function checkRand($rnd){
		$this->db->select('id');
		$this->db->from('admin_tbl');
		$this->db->where('varify',$rnd);
	    $rs = $this->db->get();
		return $rs->row('id');
	}

	public function fan_list($user_id,$limit = null, $limit_start = null){
		$this->db->select('f.*');
		$this->db->from('fan_tbl as f');
		$this->db->join('user_tbl as u','f.fan_id =u.id');
		//$this->db->group_start();
		$this->db->where("f.created_by_user_id =",$user_id);
		//$this->db->where('f.created_by_user_id =', $uid);
		//$this->db->group_end();
		$this->db->order_by("u.coins_earned", "desc");
		$q = $this->db->get();
	    $res = $q->result_array();
	    return $res;
	}

	public function following_rec($uid,$limit = null, $limit_start = null){
		$this->db->select('u.*');
		$this->db->from('user_tbl as u');
		$this->db->join('follower_tbl as f','f.follower_id =u.id');
		$this->db->group_start();
		$this->db->where('f.created_by_user_id',$uid);
		$this->db->where('u.id!=',$uid);
		$this->db->where('u.is_active =','1');
		$this->db->group_end();
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function follower_rec($uid,$limit = null, $limit_start = null){
		$this->db->select('u.*');
		$this->db->from('user_tbl as u');
		$this->db->join('follower_tbl as f','f.created_by_user_id =u.id');
		$this->db->group_start();
		$this->db->where('f.follower_id',$uid);
		$this->db->where('u.id!=',$uid);
		$this->db->where('u.is_active =','1');
		$this->db->group_end();
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
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

	public function get_country_list($limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from('telephone_tbl');
		//$this->db->where('id',$val);
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

	public function pushMessgging($uid){
		$this->db->select('d.*');
		$this->db->from('device_tbl as d');
		$this->db->join('follower_tbl as f','f.follower_id =d.id');
		$this->db->group_start();
		$this->db->where('f.created_by_user_id',$uid);
		$this->db->where('d.id!=',$uid);
		//$this->db->where('d.is_active =','1');
		$this->db->group_end();
	    $q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}

	public function update_like($uid,$send_id,$stream_id,$time){
		$this->db->select('*');
		$this->db->from('like_tbl');
		$this->db->group_start();
		$this->db->where('user_id',$uid);
		$this->db->where('send_id',$send_id);
		$this->db->where('stream_id',$stream_id);
		$this->db->group_end();
		$query = $this->db->get();
		$lid = $query->row('id');
		$data=array('created'=>$time);
		$this->db->where('id',$lid);
		$this->db->update('like_tbl',$data);
	}

        public function trans_withdrawn($uid, $limit = null, $limit_start = null){
		$this->db->select('*');
		$this->db->from('myincome_tbl');
		$this->db->where('user_id',$uid);
		$limit_start = ($limit_start==0 || $limit_start==1)?0:$limit_start-1;
		$this->db->limit($limit, ($limit_start>0)?$limit_start*$limit:$limit_start);
		$this->db->order_by("id", "desc");
		$query = $this->db->get();
		$res = $query->result_array();
	    return $res;
	}

}
?>
