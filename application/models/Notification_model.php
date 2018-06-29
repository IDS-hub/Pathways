<?php
class Notification_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}


	public function record_count($post,$aid) {
		$this->db->select('*');
		$q = $this->db->get('notification_tbl');
		//$this->db->where('status','1');
		$q = $q->num_rows();
		return $q;
	}
	public function get_notify_data($limit, $start, $post){	//print_r($post);
		$this->db->limit($limit, $start);
		$this->db->select('*');
		//$this->db->where('status','1');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('notification_tbl');
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['title'] = $rec['title'];
			$rest[$i]['message'] = $rec['message'];
			$rest[$i]['datetime'] = $rec['datetime'];
			//$rest[$i]['status'] = $rec['status'];
			$i++;
		}
	    return $rest;
	}
	public function record_add($data) {
		$this->db->insert("notification_tbl",$data);
		return $this->db->insert_id();
	}
	public function record_edit($id){
		$this->db->select('*');
		$this->db->from('notification_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$res = $q->row();
		$rest= array();
		$rest['id'] = $res->id;
		$rest['title'] = $res->title;
		$rest['message'] = $res->message;
		//$rest['status'] = $res->status;
		return $rest;
	}

	public function record_update($data,$hid){
		$this->db->where('id',$hid);
 		if($this->db->update('notification_tbl',$data)){
			return 1;
		}else{
			return 0;
		}
	}
	public function allUser(){
		//$this->db->limit(1);
		$this->db->select('*');
		$this->db->from('user_tbl as u');
		$this->db->join('device_tbl as d','d.user_id=u.id');
		$this->db->order_by("d.id", "desc");
		$this->db->where('u.is_active = "1"');
		$q = $this->db->get();
		//echo $this->db->last_query();die();
	    $res = $q->result_array();
	    return $res;
	}








}
?>
