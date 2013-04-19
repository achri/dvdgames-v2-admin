<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_pictures {
	private $CI;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library(array('upload'));
		$this->CI->load->helper(array('file','directory'));
	}
	
	function thumbs_ajax ($dvd_nama,$w = '',$h = '', $folder = './thumb/') {
		
		if ($w == '' AND $h == ''):
			$set_lebar  = '200';
			$set_tinggi = '200';
		else:
			$set_lebar	= $w;
			$set_tinggi	= $h;
		endif;
				
		$link_gbr = $dvd_nama;
		if ((file_exists('storage/'.$folder.$link_gbr))&&(!empty($link_gbr))):
			$ukuran = getimagesize('storage/'.$folder.$link_gbr);
			
			if ($ukuran[0]>$ukuran[1]): 
				$opsi['width'] = $set_lebar;						
			elseif ($ukuran[1]>$ukuran[0]):
				$opsi['height'] = $set_tinggi;
			else: 
				$opsi['width']=$set_lebar;
				$opsi['height']=$set_tinggi; 
			endif;
			$opsi['src'] = 'storage/'.$folder.$link_gbr;
			
		else:
			$opsi['src'] = 'storage/'.$folder.'na.png';
			$opsi['height'] = $set_tinggi;
		endif;
		$opsi['class'] = "ui-widget-header ui-corner-all";
		//return $set_lebar.'-'.$set_tinggi.'<br>'.$ukuran[0].'-'.$ukuran[1];
		return img($opsi);
	}
	
}
?>