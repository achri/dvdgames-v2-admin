<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

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

class  extends Controller {
	public static $link_view, $link_controller;
	function () 
	{
		parent::Controller();	
		
		// EXTRA SUB HEADER ==>
		$arrayCSS = array (
		);
		
		$arrayJS = array (
		);
		
		$data['extraSubHeadContent'] = '';
		
		if (is_array($arrayCSS))
			foreach ($arrayCSS as $css):
				$data['extraSubHeadContent'] .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
			endforeach;
		
		if (is_array($arrayJS))
			foreach ($arrayJS as $js):
				$data['extraSubHeadContent'] .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
			endforeach;
		
		// <== END EXTRA HEADER
		
		// LINK CONTROLLER & VIEW ==>
		self::$link_controller = '';
		self::$link_view = '';
		
		$data['link_view'] = self::$link_view;
		$data['link_controller'] = self::$link_controller;
		// <== END LINK CONTROLLER & VIEW
		
		// PAGE TITLE ==>
		$data['page_title'] = "";
		// <== END PAGE TITLE
		
		$this->load->vars($data);
	}
	
	function index() 
	{
	
	}
}

/* End of file .php */
/* Location: ./../.php */