<?php
class Diagnosisrequest_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}
	public function userfunc($id){
		$query = "SELECT * FROM user_tbl WHERE id = '".$id."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			$row = $rs->row();
			$id = $row->first_name.' '.$row->last_name;
			return $id;
		}else{
			return '';
		}
    }

	public function userfunc_stremlog_user_code($id){
		$query = "SELECT * FROM user_tbl WHERE id = '".$id."'";
		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 ){
			$row = $rs->row();
			$user_code = $row->user_code;
			return $user_code;
		}else{
			return '';
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

	public function record_count($aid) {
		if(isset($aid) && $aid==1){
			$this->db->where('is_approved',$aid);
		}
		$this->db->select('*');
		$q = $this->db->get('diagnosis_request_tbl');
		$q = $q->num_rows();
		//echo $this->db->last_query();
		return $q;
	}


	public function get_diagnosisrequest_data($limit, $start, $aid){	//print_r($post);
		if(isset($aid) && $aid==1){
			$this->db->where('is_approved',$aid);
		}
		$this->db->limit($limit, $start);
		$this->db->select('*');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('diagnosis_request_tbl');
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['diagnosis_request_title'] = $rec['diagnosis_request_title'];
			$rest[$i]['created'] = $rec['created'];
			$rest[$i]['is_approved'] = $rec['is_approved'];
			$i++;
		}
	    return $rest;
	}


	public function diagnosis_requestInfo($id){
		$this->db->select('*');
		$this->db->from('diagnosis_request_tbl');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}


	public function remove_url($sid=null){
		$data=array('stream_url'=>'','broadcast_id'=>'');
		$this->db->where('id',$sid);
		$this->db->update('stream_tbl',$data);
	}

	public function get_user_status($id){
		$table_name='diagnosis_request_tbl';
		$this->db->select('*');
		$this->db->where('id',$id);
		$q = $this->db->get($table_name);
	    $res = $q->row();
		$data['is_approved'] = ($res->is_approved==1)?'0':'1';
		$this->db->where('id',$id);
 		$this->db->update($table_name,$data);
	}

}
?>
