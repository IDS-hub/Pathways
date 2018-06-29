<?php
class Quiz_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}


	public function record_count($post,$aid) {
		$this->db->select('*');
		$q = $this->db->get('session_tbl');
		//$this->db->where('status','1');
		$q = $q->num_rows();
		return $q;
	}
	public function get_gift_data($limit, $start, $post){	//print_r($post);
		//$this->db->limit($limit, $start);
		//$this->db->select('*');
		//$this->db->order_by("id", "desc");
		//$this->db->where('id', 17);
		$sessionQueryQuiz = "SELECT * FROM `session_tbl` WHERE id IN('17','30','44')";
		$sessionQueryQuizfetch =  $this->db->query($sessionQueryQuiz);
		$res = $sessionQueryQuizfetch->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['title'] = $rec['title'];
			$rest[$i]['session_description'] = $rec['session_description'];
			$rest[$i]['session_summary'] = $rec['session_summary'];
			$rest[$i]['session_summary_image'] = $rec['session_summary_image'];
			$i++;
		}
	    return $rest;
	}
	public function record_add($data) {
		$this->db->insert("session_tbl",$data);
		return $this->db->insert_id();
	}
	/****public function record_edit($id){
		$this->db->select('*');
		$this->db->from('session_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$res = $q->row();
		//echo $this->db->last_query(); die();
		$rest= array();
		$rest['id'] = $res->id;
		$rest['title'] = $res->title;
		$rest['session_description'] = $res->session_description;
		$rest['session_summary'] = $res->session_summary;
		$rest['session_summary_image'] = $res->session_summary_image;
		return $rest;
	}***/
	
	public function record_edit($id){
		$this->db->select('*');
		$this->db->from('quiz_tbl');
		$this->db->where('session_id =', $id);
		$q = $this->db->get();
		$res = $q->result_array();
		//echo $this->db->last_query(); die();
		//echo '<pre>'; print_r($res); die();
		/*$rest= array();
		$rest['id'] = $res->id;
		$rest['title'] = $res->title;
		$rest['session_description'] = $res->session_description;
		$rest['session_summary'] = $res->session_summary;
		$rest['session_summary_image'] = $res->session_summary_image;
		return $rest;*/
		return $res;
	}

	public function record_update($data,$hid){
		$this->db->where('id',$hid);
 		if($this->db->update('quiz_tbl',$data)){
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

	public function tblRec($table_name,$id){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}

}
?>
