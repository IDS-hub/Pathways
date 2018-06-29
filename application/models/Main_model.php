<?php
class Main_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
	}
	
	
	function record_count($table_name,$condition,$groupbyorder,$searchvalue) 
	{
		$this->db->group_by($groupbyorder);
		
		if(count($searchvalue) > 0)
		{
			foreach($searchvalue as $searchvalue1)
			{
				$this->db->like($searchvalue1[0],$searchvalue1[1],'both');
			}
		}
		
		$fulldetails = $this->db->get_where($table_name, $condition);
		return $fulldetails->num_rows();
	}
	
	
	
	
}
?>