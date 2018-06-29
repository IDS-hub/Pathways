<?php
class Feedback_model extends CI_Model
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
				// $this->db->group_start();
				// $this->db->like('first_name',trim($post["txtSearch"]), 'both');
				// $this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
				// $this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($post["txtSearch"]), 'both');
				// $this->db->or_like('email',trim($post["txtSearch"]), 'both');
				// $this->db->group_end();
				$string = $post["txtSearch"];
			    $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
			    //preg_match_all($pattern, $string, $matches);
			    //var_dump($matches[0]);
				$this->db->group_start();
				if (preg_match($pattern,$string) === 1) {
					$this->db->or_like('email',trim($post["txtSearch"]), 'both');
				}else{
					$this->db->like('first_name', trim($post["txtSearch"]), 'both');
					$this->db->or_like('last_name',trim($post["txtSearch"]), 'both');
					$this->db->or_like("CONCAT(first_name, ' ', last_name)", trim($post["txtSearch"]), 'both');
				}
				//$this->db->where('f.status',$aid);
				$this->db->group_end();
			}
		}
		else {
			$this->db->where('status',$aid);
		}
		$this->db->select('*');
		$q = $this->db->get('feedback_tbl');
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
		// if(isset($aid)){
		// 	$this->db->where('is_active',$aid);
		// }
		//$this->db->select('*');
		//$this->db->order_by("id", "desc");
		//$q = $this->db->get('feedback_tbl');

		$this->db->select('f.*');
		$this->db->order_by("f.id", "desc");
		$this->db->from('feedback_tbl as f');
		$this->db->join('user_tbl as u','u.id=f.user_id');
		if($post!=NULL){
			if(isset($post["txtSearch"]) && $post["txtSearch"]!=""){
				$string = $post["txtSearch"];
			    $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
			    //preg_match_all($pattern, $string, $matches);
			    //var_dump($matches[0]);
				$this->db->group_start();
				if (preg_match($pattern,$string) === 1) {
					$this->db->or_like('f.email',trim($post["txtSearch"]), 'both');
				}else{
					$this->db->like('u.first_name', trim($post["txtSearch"]), 'both');
					$this->db->or_like('u.last_name',trim($post["txtSearch"]), 'both');
					$this->db->or_like("CONCAT(u.first_name, ' ', u.last_name)", trim($post["txtSearch"]), 'both');
				}
				//$this->db->where('f.status',$aid);
				$this->db->group_end();
			}
			$this->db->where('f.status',$aid);
		}else{
			$this->db->where('f.status',$aid);
		}
		$q = $this->db->get();
		//echo $this->db->last_query(); die();


		$res = $q->result_array();
		$rest= array();
		$i=0;
		foreach($res as $rec){
			$rest[$i]['id'] = $rec['id'];
			//$rest[$i]['user'] = $rec['first_name'].' '.$rec['last_name'];
			$rest[$i]['user'] = $this->fullname($rec['user_id']);
			$rest[$i]['type'] = $rec['type'];
			$rest[$i]['email'] = $rec['email'];
			$rest[$i]['desc'] = $rec['desc'];
			$rest[$i]['fdbk_image1'] = $rec['fdbk_image1'];
			$rest[$i]['fdbk_image2'] = $rec['fdbk_image2'];
			$rest[$i]['fdbk_image3'] = $rec['fdbk_image3'];
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
		$table_name='feedback_tbl';
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
