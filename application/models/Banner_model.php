<?php
class Banner_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}


	public function record_count($post,$aid) {
		$this->db->select('*');
		$q = $this->db->get('banner_tbl');
		//$this->db->where('status','1');
		$q = $q->num_rows();
		return $q;
	}
	public function get_banner_data($limit, $start, $post){	//print_r($post);
		$this->db->limit($limit, $start);
		$this->db->select('*');
		//$this->db->where('status','1');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('banner_tbl');
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			//$rest[$i]['title'] = $rec['title'];
			$rest[$i]['banner_url'] = $rec['banner_url'];
			$rest[$i]['banner_img'] = $rec['banner_img'];
			$rest[$i]['status'] = $rec['status'];
			$i++;
		}
	    return $rest;
	}
	public function record_add($data) {
		$this->db->insert("banner_tbl",$data);
		//echo $this->db->last_query(); die();
		return $this->db->insert_id();
	}
	public function record_edit($id){
		$this->db->select('*');
		$this->db->from('banner_tbl');
		$this->db->where('id =', $id);
		$q = $this->db->get();
		$res = $q->row();
		//echo $this->db->last_query(); die();
		$rest= array();
		$rest['id'] = $res->id;
		$rest['banner_url'] = $res->banner_url;
		$rest['banner_img'] = $res->banner_img;
		$rest['status'] = $res->status;
		return $rest;
	}

	public function record_update($data,$hid){
		$this->db->where('id',$hid);
 		if($this->db->update('banner_tbl',$data)){
			return 1;
		}else{
			return 0;
		}
	}


	public function tblRec($table_name,$id){
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id',$id);
		$query = $this->db->get();
		$res = $query->row();
	    return $res;
	}

	public function banner_delete_row($id){
	  $this ->db->where('id', $id);
	  $this->db->delete('banner_tbl');
	}





}
?>
