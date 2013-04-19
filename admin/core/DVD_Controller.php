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

class DVD_Controller extends CI_Controller {
	private $GOA;
	function __construct() 
	{
		parent::__construct();
		$GOA =& get_instance();
		
		$this->load->library(array("lib_login"));
		
		$unlocked = array('core_login');
		if ( ! $GOA->lib_login->is_logged_in() AND ! in_array(strtolower(get_class($this)), $unlocked))
		{
			redirect(site_url('mod_core/core_login/index'),'location');
		} 
		
		// TIME ZONE
		date_default_timezone_set('Asia/Jakarta');
	}
}

/* End of file Security.php */
/* Location: ./application/libraries/security.php */