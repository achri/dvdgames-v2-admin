<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_dvd extends CI_Model
{
	private $CI;
	function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function data_dvd($where=FALSE,$like=FALSE,$select=FALSE,$order=FALSE,$limit=FALSE,$from=FALSE,$join=FALSE)
	{
		if (!empty($select)):
			$this->db->select($select);
		endif;
		
		if (!empty($from)):
			$this->db->from($from);
		endif;
		
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where($where,NULL,FALSE);
		endif;
		
		if (is_array($like)):
			foreach ($like as $field=>$value):
				if (is_array($value)):
					foreach ($value as $type=>$value):
						$this->db->like($field,$value,$type);
					endforeach;
				else:
					$this->db->like($field,$value,'after');
				endif;
			endforeach;
		elseif ($like):
			$this->db->like('dvd_nama',$like,'after');
		endif;
		
		if (is_array($join)):
			foreach ($join as $field=>$value):
				if (is_array($value)):
					foreach ($value as $type=>$value):
						$this->db->join($field,$value,$type);
					endforeach;
				else:
					$this->db->join($field,$value);
				endif;
			endforeach;
		endif;
		
		if (is_array($order)):
			foreach ($order as $sort=>$field):
				$this->db->order_by($field,$sort);
			endforeach;
		elseif ($order):
			$this->db->order_by('dvd_nama',$order);
		endif;
		
		if (!empty($from)):
			$ret = $this->db->get();
		else:
			$ret = $this->db->get('master_dvd');
		endif;
		
		return $ret;
	}
	
	function kode_dvd($kat_id,$dvd_id=false,$parse=3) 
	{
		if ($dvd_id):
			$this->db->select('dvd_kode,kat_id');
			$this->db->where('dvd_id',$dvd_id);
			$get_dvd = $this->db->get('master_dvd')->row();
		endif;
		
		$this->db->select_max('dvd_kode','nomor');
		$this->db->where('kat_id',$kat_id);
		$get_nomor = $this->db->get('master_dvd')->row();
		
		$numcode = $get_nomor->nomor;
		$numcode = substr($numcode,4,3);
		if ($numcode == 0)
			$numcode = 0;
		$numcode++;
		
		$numcode = str_pad($numcode, $parse, "0", STR_PAD_LEFT);
		$kat_id = str_pad($kat_id, 2, "0", STR_PAD_LEFT);
				
		if ($dvd_id):
			if ($get_dvd->kat_id != $kat_id):
				$dvd_kode = "D".$kat_id.'.'.$numcode;
			else:
				$dvd_kode = $get_dvd->dvd_kode;
			endif;
		else:
			$dvd_kode = "D".$kat_id.'.'.$numcode;
		endif;
		
		return $dvd_kode;
	}
	
	function tambah_dvd($data)
	{
		return $this->db->insert('master_dvd',$data);
	}
	
	function ubah_dvd($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('dvd_id',$where);
		endif;
		return $this->db->update('master_dvd',$data);
	}
	
	function hapus_dvd($where)
	{
		// CEK relasi dvd
		
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('dvd_id',$where);
		endif;
		return $this->db->delete('master_dvd');
	}
}


/* End of file .php */
/* Location: ./../.php */