<?php if ( ! defined('BASEPATH')) exit('cant access');

// GET CONFIG
function config($name)
{
	$CI =& get_instance();
	return $CI->config->item($name);
}

// GET SESSION
function sessions($name)
{
	$CI =& get_instance();
	if ($CI->session)
		return $CI->session->userdata($name);
}