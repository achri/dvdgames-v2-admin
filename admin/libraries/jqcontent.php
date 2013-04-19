<?php

class Jqcontent
{	
	function header()
	{
	
		$arrayCSS = array (
			'asset/css/base/jquery.ui.all.css',
			'asset/css/general.css'
		);
				
		$arrayJS = array (
			'asset/js/jquery-1.4.4.js',
			'asset/js/ui/jquery-ui-1.8.9.custom.js'
		);
				
		$header = '';
				
		foreach ($arrayCSS as $css):
			$header .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
		endforeach;
		foreach ($arrayJS as $js):
			$header .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
		endforeach;

		return $header;
		
	}
	
	function jsplugins($path = '') {
		$header = '';
		if (is_array($path)):
			foreach ($path as $js):
				$header .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
			endforeach;
		else:
			$header .= '<script type="text/javascript" src="'.base_url().$path.'"/></script>';
		endif;
		
		return $header;
	}
	
	function cssplugins($path = '') {
		$header = '';
		if (is_array($path)):
			foreach ($path as $css):
				$header .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
			endforeach;
		else:
			$header .= '<link type="text/css" rel="stylesheet" href="'.base_url().$path.'"/>';
		endif;
		
		return $header;
	}
}