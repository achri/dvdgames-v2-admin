<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- Fungsi untuk database
*/

class Metadata extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}

	function list_table()
	{
		return $this->db->list_tables();
	}
	
	function list_field($table)
	{
		return $this->db->list_fields($table);
	}

}

/* End of file metadata.php */
/* Location: app/models/metadata.php */