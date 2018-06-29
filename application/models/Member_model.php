<?php
class Member_model extends CI_Model
{
	function __construct(){
		parent::__construct();
	}
	public function get_admin_data(){
		$this->db->select('*');
		$query = $this->db->get('admin_tbl');
		return $query->row_array();
	}

	public function get_admin_update($first_name, $last_name, $email, $mob_no, $member_img, $hid){
		$data['first_name']=$first_name;
		$data['last_name']=$last_name;
		$data['email']=$email;
		$data['mob_no']=$mob_no;
		if(isset($member_img) && $member_img!=''){
			$data['member_img']=$member_img;
		}
		//$data['password']=$password;
		$this->db->where('id', $hid);
                //print_r($data); die();
		return $this->db->update('admin_tbl',$data);
	}
	public function get_admin_password_update($password, $hid){
		$data['password']=$password;
		$this->db->where('id', $hid);
		return $this->db->update('admin_tbl',$data);
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
