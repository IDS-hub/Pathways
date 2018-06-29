<?php
class home_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		//$this->load->library('session');
	}

	public function get_login($uid){
		$this->db->where('user_id', $uid);
		$query = $this->db->get('qr_tbl');
		$cnt = $query->num_rows();
		if($cnt>0){
			$this->db->where('id', $uid);
			$this->db->where('is_active', '1');
			$query3 = $this->db->get('user_tbl');
			//echo $this->db->last_query();die();
			$res = $query3->row();
			//print_r($res); die();
			$cnt3 = $query3->num_rows();
			if($cnt3>0){
				$rec['id']=  $res->id;
				$rec['first_name']=  $res->first_name;
				$rec['last_name']=  $res->last_name;
				$rec['mob_no']= $res->mob_no;
				$rec['profile_image'] = ($res->profile_image!='')?$res->profile_image:(($res->profile_image2!='')?$res->profile_image2:base_url().'uploads/user/no-image.png');
				if($res->id!=''){
					$this->db->select('*');
					$this->db->from('device_tbl');
					$this->db->where('user_id',$res->id);
					$query2 = $this->db->get();
					//echo $this->db->last_query();die();
					$res2 = $query2->row();
					$rec['token']= $res2->access_token;
				}
				return $rec;
			}
		}
	}

	public function set_logout($uid){
		$this->db->where('user_id', $uid);
		$this->db->delete('qr_tbl');
	}

	public function get_token($uid){
		$this->db->select('access_token');
		$this->db->from('device_tbl');
		$this->db->where('user_id', $uid);
		$query = $this->db->get();
    	$q = $query->first_row();
		$token = $q->access_token;
		return $token;
	}

	public function get_stream_url($sid){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id', $sid);
		$query = $this->db->get();
    	$q = $query->first_row();
		//$stream_url = $q->stream_url;
		return $q;
	}

	public function userInfo($id){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id', $id);
		$query = $this->db->get();
    	$q = $query->first_row();
		//$stream_url = $q->stream_url;
		return $q;
	}

	/*public function stream_tokbox_sess_id($sid){
		$this->db->select('*');
		$this->db->from('stream_tbl');
		$this->db->where('id', $sid);
		$query = $this->db->get();
    	$q = $query->first_row();
		$tokbox_session_id = $q->tokbox_session_id;
		return $tokbox_session_id;
	}*/

	public function checkAccessTokenExistOrNot($data){
		//echo "dasfas";
		//print_r($data);
		$this->db->select('*');
		$this->db->from('device_tbl');
		$this->db->where('user_id', $data['usrId']);
		$this->db->where('access_token', $data['token']);
		$query = $this->db->get();
		//print_r($query->row());
		//exit;
                if($query->first_row()){
			return true;
		}else{
			return false;
		}
	}




}
?>
