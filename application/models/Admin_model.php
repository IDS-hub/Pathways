<?php
class Admin_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}

	public function getUserDeatils($id){
		$this->db->select('id,first_name,last_name,email,mob_no,member_img,username');
		$this->db->where('id',$id);
		$this->db->from('admin_tbl');
		$query = $this->db->get();
		$res = $query->row();

	    return $res;
	}

}
?>
