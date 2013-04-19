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

class App_init extends DVD_Controller {
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
		
		$this->load->vars($output);
		
		log_message('debug', "Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{
		$this->load->library(array(
			"lib_login",
		));
		
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{		
		$CSS['remote'] = array (
			// JQUERY
			'jquery/themes/'.WEB_THEMES.'/jquery.ui.all.css',
			'jquery/plugins/table/jqGrid/css/ui.jqgrid.css',
			//'asset/src/jQuery/plugins/tables/datagrid/datagrid.css',
			
			'jquery/plugins/tooltip/qtip/jquery.qtip.min.css',
			
			//'jquery/plugins/form/fileuploader/fileuploader.css',
		);
		
		$CSS['local'] = array (
			//'asset/css/jqgrid.patch.css',
			'asset/css/general.css',
			'asset/css/helper/dialog.qtip.css',
		);
		
		$JS['remote'] = array (
			// JQUERY
			'jquery/core/jquery-1.5.2.js',
			'jquery/ui/jquery-ui-1.8.12.custom.js',
			
			'jquery/plugins/table/jqGrid/src/i18n/grid.locale-en.js',
			'jquery/plugins/table/jqGrid/js/jquery.jqGrid.min.js',
			//'asset/src/jQuery/plugins/tables/datagrid/datagrid.js',
			'jquery/plugins/form/jquery.form.js',
			'jquery/plugins/form/jquery.autoNumeric.js',
			'jquery/plugins/form/ajaxupload/ajaxupload.js',
			//'jquery/plugins/form/fileuploader/fileuploader.js',
			
			'jquery/plugins/tooltip/qtip/jquery.qtip.min.js',
			
			// ADDITIONAL
			//'jquery/helper/ui.jquery.helper.js',
			'jquery/owner/autoNumeric.js',
			'jquery/owner/validasi.js',
			'jquery/owner/dialog.js',
		);
		
		$JS['local'] = array (	
			// ADDITIONAL
			'asset/js/general.js',
			'asset/js/helper/dialog.qtip.js',
		);
		
		$output['extraHeadContent'] = css_header($CSS).js_header($JS);
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'app_init';
		self::$link_view = 'mod_dvd';
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
		// private variable
		$output['title'] = "DVDGAMES-ONLINE Store (Admin)";
		$output['header_title'] = "DVDGames-Online.COM";
		$output['header_subtitle'] = "Pembelian DVD Games Online";
		
		return $output;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{
		$output[''] = '';
		$this->load->view('index',$output);
	}
	
	function log_out()
	{
		$this->lib_login->log_out();
	}
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */