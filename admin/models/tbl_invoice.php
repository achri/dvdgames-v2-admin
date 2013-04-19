<?php

class Tbl_invoice extends CI_Model
{
	function __construct() 
	{
	
	}
	
	function update_invoice($where,$data)
	{
		if(is_array($where))
			foreach ($where as $key=>$val)
				$this->db->where($key,$val);
		elseif ($where)
			$this->db->where('inv_id',$where);
		$this->db->update('invoice',$data);
	}
}