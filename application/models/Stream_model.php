<?php
class Stream_model extends CI_Model
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
			$this->db->where('is_featured',$aid);
		}
		$this->db->select('*');
		$q = $this->db->get('stream_tbl');
		$q = $q->num_rows();
		//echo $this->db->last_query();
		return $q;
	}
	public function get_stream_data($limit, $start, $aid){	//print_r($post);
		if(isset($aid) && $aid==1){
			$this->db->where('is_featured',$aid);
		}
		$this->db->limit($limit, $start);
		$this->db->select('*');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('stream_tbl');
		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['is_featured'] = $rec['is_featured'];
			$rest[$i]['tokbox_session_id'] = $rec['tokbox_session_id'];
			$rest[$i]['stream_url'] = $rec['stream_url'];
			$rest[$i]['viewer'] = $rec['view_cnt'];
			$rest[$i]['fans'] = $rec['fan_cnt']; //$this->fanfunc($rec['created_by_user_id']);
			$rest[$i]['created_by_user_id'] = $this->userfunc($rec['created_by_user_id']);
			$rest[$i]['created'] = $rec['created'];
			$i++;
		}
	    return $rest;
	}

	public function get_stream_data2($post){
		if($post!=NULL){
			if(isset($post["chkval"]) && $post["chkval"]==1){
				$this->db->where('is_featured','1');
			}
		}
		$this->db->select('*');
		$this->db->order_by("id", "desc");
		$q = $this->db->get('stream_tbl');
	    $res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			$rest[$i]['is_featured'] = $rec['is_featured'];
			$rest[$i]['tokbox_session_id'] = $rec['tokbox_session_id'];
			$rest[$i]['stream_url'] = $rec['stream_url'];
			$rest[$i]['viewer'] = $rec['view_cnt'];
			$rest[$i]['fans'] = $rec['fan_cnt']; //$this->fanfunc($rec['created_by_user_id']);
			$rest[$i]['created_by_user_id'] = $this->userfunc($rec['created_by_user_id']);
			$rest[$i]['created'] = $rec['created'];
			$i++;
		}
	    return $rest;
	}


	public function remove_url($sid=null){
		$data=array('stream_url'=>'','broadcast_id'=>'');
		$this->db->where('id',$sid);
		$this->db->update('stream_tbl',$data);
	}







}
?>
