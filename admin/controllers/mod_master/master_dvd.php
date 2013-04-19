<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	30/03/2011
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- Class First Loader
*/

class Master_dvd extends DVD_Controller {
	// public variable
	public static $link_controller, $link_view;
	
	// constructor
	function __construct () 
	{
		parent::__construct();	
	
		$class = get_class($this);
		
		$this->_loader_class();
		
		$output = array();
		$output += $this->_content_init();
		$output += $this->_public_init();
		$output += $this->_variable_init();
		
		$output['class_name'] = $class;
		
		$this->load->vars($output);
		
		log_message('debug', "Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{
		$this->load->library(array(
			"lib_pictures","lib_files"
		));
		$this->load->model(array(
			"metadata","jqgrid_model","tbl_dvd","tbl_kategori","tbl_cart"
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$arrayCSS = array (
		);
		
		$arrayJS = array (
		);
		
		$output['extraSubHeadContent'] = '';//$content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'mod_master/master_dvd';
		self::$link_view = self::$link_controller;
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{
		$output['header_title'] = "DVDGames-Online.COM";
		
		$output['list_kategori'] = $this->tbl_kategori->data_kategori();
		return $output;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index()
	{
		$output[''] = '';
		$this->load->view(self::$link_view.'/dvd_main_view',$output);
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$kat_id
	// @return	: JSON array string
	function get_data()
	{
		$table = "master_dvd";			
		$result = $this->jqgrid_model->get_data($table,false,false,false,
		array(
			array(
				"table" => "master_kategori",
				"join" => "master_kategori.kat_id = ".$table.".kat_id",
				"fields" => array("kat_id","kat_nama"),
				"type"	=> "inner"
			)
		),false,false);		
		echo json_encode($result);
	}
	
	function ajaxupload()
	{
		$filename = $this->lib_files->upload_ajax();
		log_message('debug',$filename);
		echo $filename.'|';
	}
	
	function show_photo($filename,$thumb_folder	= 'temp') {
		echo $this->lib_pictures->thumbs_ajax($filename,225,225,$thumb_folder.'/');
	}
	
	function set_kode($kat_id,$dvd_id=false) 
	{
		echo $this->tbl_dvd->kode_dvd($kat_id,$dvd_id);
	}
	
	function tambah_dvd()
	{		
		$cek_dvd = array();
		
		// METADATA FIELDs TABLE
		$get_field = $this->metadata->list_field('master_dvd');
		foreach ($_POST as $name=>$value):
			$value = trim($value);
			// SYNC POSTs AND FIELDs
			if ($value != '' && in_array($name,$get_field)):
				switch ($name):
					case 'dvd_nama': 
						$data[$name] = strtoupper($value); 
						$cek[$name] = $data[$name];
					break;
					case 'kat_id':
						$data[$name] = $value; 
						$cek[$name] = $value;
					break;
					case 'dvd_gambar': 
						$data[$name] = $this->lib_files->copy_image($value);
						$this->lib_files->delete_image($value);
					break;
					case 'dvd_release': 
						$date_create = date_create($value);
						if ($date_create)
							$data[$name] = date_format($date_create,'Y-m-d'); 
					break;
					default: $data[$name] = $value; break;
				endswitch;
			endif;
		endforeach;
		
		// CEK DUPLIKASI
		foreach ($cek as $sel=>$val):
			$this->db->where($sel,$val);
		endforeach;
		
		if ($this->db->get('master_dvd')->num_rows() <= 0):
			$data['created'] = date('Y-m-d G:i:s');
			if ($this->tbl_dvd->tambah_dvd($data)):
				echo "sukses";
			endif;
		endif;
	}
	
	function ubah_dvd()
	{
		// METADATA FIELDs TABLE
		$get_field = $this->metadata->list_field('master_dvd');
		foreach ($_POST as $name=>$value):
			$value = trim($value);
			// SYNC POSTs AND FIELDs
			//if ($value != '' && in_array($name,$get_field)):
			if (in_array($name,$get_field)):
				switch ($name):
					case 'dvd_nama': $data[$name] = strtoupper($value); break;
					case 'dvd_gambar': 
						$dvd_gambar_awal = $this->input->post('dvd_gambar_awal');
						if ($dvd_gambar_awal != '' && $dvd_gambar_awal != $value):
							$this->lib_files->delete_image($dvd_gambar_awal,'dvd');
							$data[$name] = $this->lib_files->copy_image($value);
							$this->lib_files->delete_image($value);
						elseif ($dvd_gambar_awal == ''):
							$data[$name] = $this->lib_files->copy_image($value);
							$this->lib_files->delete_image($value);
						endif;
					break;
					case 'dvd_release': 
						$date_create = date_create($value);
						if ($date_create)
							$data[$name] = date_format($date_create,'Y-m-d'); 
					break;
					default: $data[$name] = $value; break;
				endswitch;
			endif;
		endforeach;
		
		$where['dvd_id'] = $data['dvd_id'];
		unset ($data['dvd_id']);
		
		if ($this->tbl_dvd->ubah_dvd($where,$data)):
			echo json_encode($data);
		endif;
	}
	
	function hapus_dvd($dvd_id)
	{
		$where['dvd_id'] = $dvd_id;
		// CEK ON CART
		$in_cart = $this->tbl_cart->get_cart_detail($where)->num_rows();
		if (!$in_cart)
		{
			$where['dvd_status'] = 0;
			$get_dvd = $this->tbl_dvd->data_dvd($where);
			if ($get_dvd->num_rows() > 0)
			{
				if ($get_dvd->row()->dvd_gambar != '') 
					$this->lib_files->delete_image($get_dvd->row()->dvd_gambar,'dvd');
				if ($this->tbl_dvd->hapus_dvd($where))
					$this->lib_files->delete_all_images();
			} else
				echo "status msh 1";
		} else
			echo "ada penjualan";
	}
	
	function gridData_kategori()
	{
		echo "<select><option value=''>SEMUA</option>";
		$get_kategori = $this->tbl_kategori->data_kategori();
		if ($get_kategori->num_rows() > 0) 
		{
			foreach($get_kategori->result() as $rows)
				echo "<option value='".$rows->kat_nama."'>".$rows->kat_nama."</option>";
		} else 
			echo "<option value=''>Kosong</option>";
			
		echo "</select>";
	}
	
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */