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

class Core_login extends CI_Controller {
	// GLOBAL VARIABLE
	public $link_view, $link_controller;
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
		
		$this->load->model(array(
			"tbl_user",
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
			'jquery/themes/'.WEB_THEMES.'/jquery.ui.all.css',
		);
		$CSS['local'] = array (
			'asset/css/body.css',
			'asset/css/general.css',
			'asset/css/login_form.css',
		);
		
		$JS['remote'] = array (
			'jquery/core/jquery-1.5.2.js',
			'jquery/ui/jquery-ui-1.8.12.custom.js',
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
		$this->link_controller = 'mod_core/core_login';
		$this->link_view = 'mod_core/core_login';
		$output['link_view'] = $this->link_view;
		$output['link_controller'] = $this->link_controller;	
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{		
		// private variable
		$output['title'] = "DVD GAMES ONLINE ADMIN";
		$output['header_title'] = "DVD GAMES ONLINE ADMIN";
		$output['header_subtitle'] = "solution for your bussiness";
		
		$output['login_form'] = 'LOGIN';
		$output['login_username'] = 'User ID';
		$output['login_password'] = 'Password';
		$output['login_submit'] = 'Login';
		$output['login_reset'] = 'Reset';
		
		return $output;
	}

	function index()
	{
		$data['extraSubHeadContent'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_id').focus();
					})
				</script>\n";
				
		$usr_id = $this->input->post('usr_id');
		$login_number = $this->session->userdata('login_number');

		if (isset($usr_id) && $usr_id != '' && $login_number != 1)
		{
			$this->lib_login->login_step1();
		}
		else if($login_number == 1) {
			$this->lib_login->login_step2();
		}
		else
		{
			
			$data['login_msg'] = 'Input First Password';
			$data['user_val'] = "";
			$data['user_readonly'] = "";
			$this->load->view($this->link_view.'/login_main_view', $data);
		}
	}

	// --------------------------------------------------------------------
	function second_login()
	{
			$usr_login = $this->session->userdata('usr_login');
			$data['extraSubHeadContent'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_pwd').focus();
					})
				</script>\n";
			
			$data['login_msg'] = 'Next, Input Second Password';
			$data['user_val']= $usr_login;
			$data['user_readonly']="readonly";
			$this->load->view($this->link_view.'/login_main_view', $data);
	}
	
	function login_fail()
	{
			$data['extraSubHeadContent'] = "<script type=\"text/javascript\">
					$(document).ready(function () {
						$('#usr_id').focus();
					})
				</script>\n";
			
			$data['login_msg'] = "User ID/First Password/Second Password it's failure";
			$data['user_val']="";
			$data['user_readonly']="";
			$this->load->view($this->link_view.'/login_main_view', $data);
	}
	
	function log_out()
	{
		$this->lib_login->log_out();
	}
	
}

/* End of file core.php */
/* Location: ./application/controllers/core.php */