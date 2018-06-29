<?php
class User_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}
	public function record_count($post,$aid) {
		if(isset($aid)){
			$this->db->where('is_active',$aid);
		}
		if($post!=NULL){
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($post["txtSearch"]), 'both');
				$this->db->or_like('mob_no',trim($post["txtSearch"]), 'both');
				$this->db->group_end();
			}
			/*if($post["clearval"]=="clear"){
				$post["txtSearch"]="";
				$this->db->where('is_active','1');
			}
		 	else if($post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',$post["txtSearch"], 'both');
				$this->db->or_like('last_name',$post["txtSearch"], 'both');
				$this->db->or_like('mob_no',$post["txtSearch"], 'both');
				$this->db->group_end();
				if($post["selVal"]!=""){
					$this->db->where('is_active',$post["selVal"]);
				}
			}else if($post["selVal"]!=""){
				$this->db->where('is_active',$post["selVal"]);
			}*/
		}
		else {
			$this->db->where('is_active',$aid);
		}
		$this->db->select('*');
		$q = $this->db->get('user_tbl');
		$q = $q->num_rows();
		//echo $this->db->last_query();
		return $q;
	}
	public function get_user_data($limit, $start, $aid, $post){	//print_r($post);
		//echo 'eee ='.$condition;
		if(isset($aid)){
			$this->db->where('is_active',$aid);
		}
		if($post!=NULL){
			if(isset($post["type"]) && $post["type"]!=''){
				$start = 0;
			}
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($post["txtSearch"]), 'both');
				$this->db->or_like('mob_no',trim($post["txtSearch"]), 'both');
				$this->db->group_end();
			}
			/*if($post["clearval"]=="clear"){
				$post["txtSearch"]="";
				$this->db->where('is_active','1');
			}
		 	else if($post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',$post["txtSearch"], 'both');
				$this->db->or_like('last_name',$post["txtSearch"], 'both');
				$this->db->or_like('mob_no',$post["txtSearch"], 'both');
				$this->db->group_end();
				if($post["selVal"]!=""){
					$this->db->where('is_active',$post["selVal"]);
				}
			}else if($post["selVal"]!=""){
				$this->db->where('is_active',$post["selVal"]);
			}*/
		}
		//else {
			//$this->db->where('is_active',$aid);
		//}
		$this->db->limit($limit, $start);
		$this->db->select('*');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('user_tbl');
		//echo $this->db->last_query();
	    $res = $q->result_array();
	    return $res;
	}

	public function getDataForD($id){
		$this->db->select('diagnosis.*,user_diagnosis.user_id,user_diagnosis.diagnosis_title as user_diagnosis_title');
		$this->db->from('user_diagnosis_tbl as user_diagnosis');
		//$this->db->order_by("user.id", "desc");
		$this->db->join('diagnosis_tbl as diagnosis', 'diagnosis.id = user_diagnosis.diagnosis_id','left');
		$this->db->where(['user_diagnosis.user_id'=>$id]);
		$q = $this->db->get();
		//echo $this->db->last_query();
		$res = $q->result_array();
		$diagnosis = '';
		foreach ($res as $key => $value) {
			if($value['title']){
				if($diagnosis){
					$diagnosis = $diagnosis .' , ' .$value['title'];
				}else{
					$diagnosis = $value['title'];
				}
			}
		}

	    return $diagnosis;
	}

	public function get_user_data_new($limit, $start, $aid, $post){
		if(isset($aid)){
			$this->db->where('is_active',$aid);
		}
		if($post!=NULL){
			if(isset($post["type"]) && $post["type"]!=''){
				$start = 0;
			}
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($post["txtSearch"]), 'both');
				$this->db->or_like('mob_no',trim($post["txtSearch"]), 'both');
				$this->db->group_end();
			}
			/*if($post["clearval"]=="clear"){
				$post["txtSearch"]="";
				$this->db->where('is_active','1');
			}
		 	else if($post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',$post["txtSearch"], 'both');
				$this->db->or_like('last_name',$post["txtSearch"], 'both');
				$this->db->or_like('mob_no',$post["txtSearch"], 'both');
				$this->db->group_end();
				if($post["selVal"]!=""){
					$this->db->where('is_active',$post["selVal"]);
				}
			}else if($post["selVal"]!=""){
				$this->db->where('is_active',$post["selVal"]);
			}*/
		}
		//else {
			//$this->db->where('is_active',$aid);
		//}

		//$this->db->limit($limit, $start);
		$this->db->select('user.*,diagnosis.id as d_id,diagnosis.diagnosis_id,diagnosis.diagnosis_title')->from('user_tbl as user');
		$this->db->order_by("user.id", "desc");
		$this->db->join('user_diagnosis_tbl as diagnosis', 'user.id = diagnosis.diagnosis_id','outer');
		$this->db->where(['user.id'=>54]);
		$q = $this->db->get();
		//echo $this->db->last_query();
	    $res = $q->result_array();
	    return $res;
	}

	public function get_user_status($id){
		$table_name='user_tbl';
		$this->db->select('*');
		$this->db->where('id',$id);
		$q = $this->db->get($table_name);
                $res = $q->row();
		$data['is_active'] = ($res->is_active==1)?'0':'1';
		$this->db->where('id',$id);
 		$this->db->update($table_name,$data);
	}

        public function get_user_freeaccess($id){
		$table_name='user_tbl';
		$this->db->select('*');
		$this->db->where('id',$id);
		$q = $this->db->get($table_name);
                $res = $q->row();
		$data['free_access'] = ($res->free_access==1)?'0':'1';
		$this->db->where('id',$id);
 		$this->db->update($table_name,$data);
	}

	public function get_userlist(){
		$this->db->select('id,first_name,last_name');
		$this->db->where('is_active','1');
		$this->db->from('user_tbl');
		$query = $this->db->get();
		$res = $query->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['first_name'] = $rec['first_name'];
			$rest[$i]['last_name'] = $rec['last_name'];
			$i++;
		}
	    return $rest;
	}

	public function set_earn($uid,$earn){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
		$query = $this->db->get();
		$coin = $query->row('coins_earned');
		$coin = $coin + $earn;
		$data=array('coins_earned'=>$coin);
		$this->db->where('id',$uid);
		$this->db->update('user_tbl',$data);
	}

	public function resetPassword($uid,$password,$current_password){
		if($uid){
			$this->db->select('id');
			$this->db->from('user_tbl');
			$this->db->where('id',$uid);
			$this->db->where('password',$current_password);
			$query = $this->db->get();
			print_r($query->row());
			if($query->row('id')){
				$this->db->where('id',$uid);
				return $this->db->update('user_tbl',array('password'=>$password));
			}else{
				return 0;
			}
		}else{
			return 0;
		}

	}


        public function record_edit($id){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$res = $q->row();
		//echo $this->db->last_query(); die();
		$rest= array();
		$rest['id'] = $res->id;
		$rest['first_name'] = $res->first_name;
		$rest['last_name'] = $res->last_name;
		$rest['email'] = $res->email;
		$rest['password'] = $res->password;
		return $rest;
	}

        	public function record_update($data,$hid){
		$this->db->where('id',$hid);
 		if($this->db->update('user_tbl',$data)){
			return 1;
		}else{
			return 0;
		}
	}

}
?>
