<?php
class Income_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}


	public function record_count($post,$aid) {
		if(isset($aid)){
			$this->db->where('status',$aid);
		}
		if($post!=NULL){
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('mob_no',trim($post["txtSearch"]), 'both');
				$this->db->group_end();
			}
		}else {
			$this->db->where('status',$aid);
		}
		$this->db->select('*');
		$q = $this->db->get('myincome_tbl');
		$q = $q->num_rows();
		return $q;
	}
	public function get_incm_data($limit, $start, $aid, $post){	//print_r($post);
		/*if($post!=NULL){
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('first_name',$post["txtSearch"], 'both');
				$this->db->or_like('last_name',$post["txtSearch"], 'both');
				$this->db->group_end();
			}
		}*/
		$this->db->limit($limit, $start);

		// $this->db->select('*');
		// $this->db->order_by("id", "desc");
		// $q = $this->db->get('myincome_tbl');

		$this->db->select('m.*');
		$this->db->order_by("m.id", "desc");
		$this->db->from('myincome_tbl as m');
		$this->db->join('user_tbl as u','u.id=m.user_id');
		if($post!=NULL){
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$this->db->group_start();
				$this->db->like('u.first_name', trim($post["txtSearch"]), 'both');
				$this->db->or_like('u.last_name',trim($post["txtSearch"]), 'both');
				$this->db->or_like('m.mob_no',trim($post["txtSearch"]), 'both');
				$this->db->group_end();
			}
			$this->db->where('m.status',$aid);
		}else{
			$this->db->where('m.status',$aid);
		}
		$q = $this->db->get();


		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['user'] = $this->fullname($rec['user_id']);
			$rest[$i]['mob_no'] = $rec['mob_no'];
			$rest[$i]['email'] = $rec['email'];
			$rest[$i]['mycoin'] = $rec['mycoin'];
			$rest[$i]['wallet_type'] = $rec['wallet_type'];
			//$rest[$i]['bank_name'] = $rec['bank_name'];
			$rest[$i]['ifs_code'] = $rec['ifs_code'];
			$rest[$i]['acc_no'] = $rec['acc_no'];
			$rest[$i]['acc_holder'] = $rec['acc_holder'];
			$rest[$i]['created'] = $rec['created'];
			$rest[$i]['status'] = $rec['status'];
			$i++;
		}
	    return $rest;
	}

	function fullname($uid){
		$this->db->select('*');
		$this->db->from('user_tbl');
		$this->db->where('id',$uid);
		$rs = $this->db->get();
		$nm = $rs->row();
		return $nm->first_name.' '.$nm->last_name;
	}

	public function get_user_status($id){
		$table_name='myincome_tbl';
		$this->db->select('*');
		$this->db->where('id',$id);
		$q = $this->db->get($table_name);
	    $res = $q->row();
		$data['status'] = ($res->status==1)?'0':'1';
		$this->db->where('id',$id);
 		$this->db->update($table_name,$data);
	}







}
?>
