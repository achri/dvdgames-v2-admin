<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_kategori extends CI_Model
{
	private $CI;
	function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function data_kategori($where=FALSE,$like=FALSE,$select=FALSE)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('kat_id',$where);
		endif;
		
		$this->db->order_by('kat_nama');
		
		return $this->db->get('master_kategori');
	}

	function tambah_kategori($data)
	{
		return $this->db->insert('master_kategori',$data);
	}
	
	function ubah_kategori($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('kat_id',$where);
		endif;
		return $this->db->update('master_kategori',$data);
	}
	
	function hapus_kategori($where)
	{
		if (FALSE === empty($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('kat_id',$where);
		endif;
		return $this->db->delete('master_kategori');
	}
}


/* End of file .php */
/* Location: ./../.php */