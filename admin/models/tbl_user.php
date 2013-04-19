<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 @author		Achri
 @date creation	
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- 
*/

class Tbl_user extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	
	function data_user($where=false,$like=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		if (is_array($like)):
			foreach ($like as $field=>$value):
				$this->db->like($field,$value);
			endforeach;
		endif;
		
		return $this->db->get('sys_user');
	}
	
	function tambah_user($data)
	{
		return $this->db->insert('sys_user',$data);
	}
	
	function ubah_user($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->update('sys_user',$data);
	}
	
	function hapus_user($where)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		endif;
		
		return $this->db->delete('sys_user');
	}

}