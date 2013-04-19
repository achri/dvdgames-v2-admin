<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class  extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	/* ==============================================================
	// @info	: Active Record Function
	// @default	: get_data($select,$from);
	// @params 	: $select,$from,$where,$like,$join,$sort,$grup,$limit
	// @return 	: [object_record]
	// ============================================================== */
	
	function get_data($select,$from,$where=FALSE,$like=FALSE,$join=FALSE,$sort=FALSE,$grup=FALSE,$limit=FALSE) 
	{
		// SELECT FUNC
		// @params	: $select = array('field');
		$this->db->select($select);
		
		// FROM FUNC
		// @params	: $from = 'table';
		$this->db->from($from);
		
		// WHERE FUNC 
		// @params	: $where['field']['tipe'] = 'value';
		if (is_array($where)):
			foreach ($where as $field=>$value):
				if (is_array($value)):
					foreach ($value as $tipe=>$values):
						switch ($tipe):
							case 'where_in': $this->db->where_in($field,$values); break;
							case 'where_not_in': $this->db->where_not_in($field,$values); break;
							case 'or_where_in': $this->db->or_where_not_in($field,$values); break;
							case 'or_where_not_in': $this->db->or_where_not_in($field,$values); break;
						endswitch;
					endforeach;
				else:
					$this->db->where($field,$value);
				endif;
			endforeach;
		else:
			$this->db->where($where);
		endif;
		
		// LIKE FUNC
		// @params	: $like['field']['tipe']['segment'] = 'value';
		if (is_array($like)):
			foreach ($like as $field=>$value):
				if (is_array($value)):
					foreach ($value as $tipe=>$values):
						if (is_array($values)):
							foreach ($values as $segment=>$value):
								switch ($tipe):
									case 'or_like': $this->db->or_like($field,$values,$segment); break;
									case 'not_like': $this->db->not_like($field,$values,$segment); break;
									case 'or_not_like': $this->db->or_not_like($field,$values,$segment); break;
								endswitch;
							endforeach;
						else:
							switch ($tipe):
								case 'or_like': $this->db->or_like($field,$values); break;
								case 'not_like': $this->db->not_like($field,$values); break;
								case 'or_not_like': $this->db->or_not_like($field,$values); break;
							endswitch;
						endif;
					endforeach;
				else:
					$this->db->like($field,$value);
				endif;
			endforeach;
		else:
			$this->db->like($where);
		endif;
		
		// JOIN FUNC
		// @params	: $join['field']['tipe'] = 'value';
		if (is_array($join)):
			foreach ($join as $field=>$value):
				if (is_array($value)):
					foreach ($value as $tipe=>$values):
						$this->db->join($field,$values,$tipe);
					endforeach;
				else:
					$this->db->join($field,$value);
				endif;
			endforeach;
		else:
			$this->db->join($join);
		endif;
		
		// ORDER BY FUNC
		// @params	: $sort['field'] = 'order';
		if (is_array($sort)):
			foreach ($sort as $field=>$by):
				$this->db->order_by($field,$by);
			endforeach;
		else:
			$this->db->order_by($sort);
		endif;

		// GRUP BY FUNC
		// @params	: $grup = array('field');
		if ($grup!=FALSE):
			foreach ($grup as $field=>$by):
				$this->db->order_by($field,$by);
			endforeach;
		else:
			$this->db->order_by($sort);
		endif;
		
		return $this->db->get();
		
	}

}

/* End of file .php */
/* Location: ./../.php */