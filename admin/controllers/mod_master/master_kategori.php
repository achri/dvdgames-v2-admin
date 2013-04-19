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

class Master_kategori extends DVD_Controller {
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
			"metadata","jqgrid_model","tbl_kategori","tbl_dvd"
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$content = "";
		
		$arrayCSS = array (
		);
		
		$arrayJS = array (			
		);
		
		if (is_array($arrayCSS))
		foreach ($arrayCSS as $css):
			$content .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url().$css."\"/>\n";
		endforeach;
		
		if (is_array($arrayJS))
		foreach ($arrayJS as $js):
			$content .= "<script type=\"text/javascript\" src=\"".base_url().$js."\"/></script>\n";
		endforeach;
		
		// BIND OPTIONAL JS HERE =========>
		$content .= "";
		
		$output['extraSubHeadContent'] = $content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'mod_master/master_kategori';
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
		
		//$output['list_kategori'] = $this->tbl_kategori->data_kategori();
		return $output;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index()
	{
		$output[''] = '';
		$this->load->view(self::$link_view.'/kategori_main_view',$output);
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$kat_id
	// @return	: JSON array string
	function get_data()
	{
		$table = "master_kategori";			
		$result = $this->jqgrid_model->get_data($table);		
		echo json_encode($result);
	}
	
	function ajaxupload()
	{
		if ($file_name = $this->lib_files->upload_ajax())
			echo $file_name;
	}
	
	function show_photo($filename,$folder = 'temp') {
		echo $this->lib_pictures->thumbs_ajax($filename,225,225,$folder.'/');
	}
	
	function tambah_kategori()
	{		
		// METADATA FIELDs TABLE
		$get_field = $this->metadata->list_field('master_kategori');
		foreach ($_POST as $name=>$value):
			$value = trim($value);
			// SYNC POSTs AND FIELDs
			if ($value != '' && in_array($name,$get_field)):
				switch ($name):
					case 'kat_nama': $data[$name] = strtoupper($value); break;
					case 'kat_gambar': 
						$data[$name] = $this->lib_files->copy_image($value,'kategori');
						$this->lib_files->delete_image($value);
					break;
					default: $data[$name] = $value; break;
				endswitch;
			endif;
		endforeach;
		
		if ($this->tbl_kategori->tambah_kategori($data)):
			echo "sukses";
		endif;
	}
	
	function ubah_kategori()
	{
		// METADATA FIELDs TABLE
		$get_field = $this->metadata->list_field('master_kategori');
		foreach ($_POST as $name=>$value):
			$value = trim($value);
			// SYNC POSTs AND FIELDs
			if ($value != '' && in_array($name,$get_field)):
				switch ($name):
					case 'kat_nama': $data[$name] = strtoupper($value); break;
					case 'kat_gambar': 
						$kategori_gambar_awal = $this->input->post('kat_gambar_awal');
						if ($kategori_gambar_awal != '' && $kategori_gambar_awal != $value):
							$this->lib_files->delete_image($kategori_gambar_awal,'kategori');
							$data[$name] = $this->lib_files->copy_image($value,'kategori');
							$this->lib_files->delete_image($value);
						elseif ($kategori_gambar_awal == ''):
							$data[$name] = $this->lib_files->copy_image($value,'kategori');
							$this->lib_files->delete_image($value);
						endif;
					break;
					default: $data[$name] = $value; break;
				endswitch;
			endif;
		endforeach;
		
		$where['kat_id'] = $data['kat_id'];
		unset ($data['kat_id']);
		
		if ($this->tbl_kategori->ubah_kategori($where,$data)):
			echo "sukses";
		endif;
	}
	
	function hapus_kategori($kat_id)
	{
		$where['kat_id'] = $kat_id;
		// CEK DVD
		$dvd_in_use = $this->tbl_dvd->data_dvd($where)->num_rows();
		if (!$dvd_in_use) {
			if (!$this->tbl_kategori->hapus_kategori($where))
				echo "gagal";
		}	else
			echo $dvd_in_use."terpakai";
	}
	
}

/* End of file master_kategori.php */
/* Location: ./app-imp/controllers/master_kategori.php */