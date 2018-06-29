<?php
class Frntlogin_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}
	function get_random_user(){
        $this->db->order_by('id', 'RANDOM');
        $this->db->limit(1);
        $result = $this->db->get('user_tbl');
        return $result->row();
    }
	function set_qrcode($rnd,$ip){
		$this->db->select('*');
		$this->db->from('qr_tbl');
		$this->db->where('ip',$ip);
		//$this->db->where('qrcode',$rnd);
		$query = $this->db->get();
		$res = $query->row();
		//if(count($res)<1){
			$data = array('qrcode'=>$rnd,'ip'=>$ip);
	        $this->db->insert('qr_tbl', $data);
		// }else{
		// 	$data=array('qrcode'=>$rnd);
		// 	$this->db->where('ip',$ip);
		// 	$this->db->update('qr_tbl',$data);
		// }
    }
	function random_string($length){
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		$key .=	date('njs');
		return $key;
	}
	function qr_chk($rnd,$id){
                //echo $id;
		$this->db->select('*');
		$this->db->from('qr_tbl');
		$this->db->group_start();
		$this->db->where('qrcode',$rnd);
		//$this->db->where('user_id!=',$id);
		$this->db->group_end();
		$query = $this->db->get();
		$res = $query->row();
                //echo '<pre>'; print_r($res); exit;
		$fg=0;
		if(count($res)>0){
			$data = array('user_id'=>$id);
			$this->db->where('qrcode',$rnd);
			$this->db->update('qr_tbl',$data);

			$access_token = $this->random_string(16);
			$data = array('device_token' =>'','access_token'=>$access_token);
			$this->db->where('user_id',$id);
			$this->db->update('device_tbl',$data);
			$fg = 1;
		}
		return $fg;
	}

	function check_user_login($rnd){
		$this->db->select('*');
		$this->db->from('qr_tbl');
		$this->db->where('qrcode',$rnd);
		$query = $this->db->get();
		//echo $this->db->last_query();die();
		$res = $query->row();
		return $res;
	}

	public function deleteQr($uid){
		$this->db->where('user_id', $uid);
		$this->db->delete('qr_tbl');
	}

	public function deleteQrTableUnusedData(){
		$date = date("y-m-d");
		$date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
		//exit;
		$this->db->where('created <', $date);
		$this->db->delete('qr_tbl');
	}
}
