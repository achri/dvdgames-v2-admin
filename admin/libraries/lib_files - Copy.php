<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_files {
	private $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
		$this->CI->load->library(array('upload','image_lib'));
		$this->CI->load->helper(array('file'));
	}
	
	// @info	: UPLOAD WITH CI AND RESIZE
	// @params	: $imgname = Desire name
	// @params	: $img_path= Target folder
	// @return	: STRING (File Name)
	function upload_this($imgname,$img_path) 
	{
		$thumb_folder	= 'storage/'.$img_path;
		$temp_folder	= 'storage/temp/';
		if ($CI->upload->do_upload()):
			$image = $CI->upload->data();
			$imgfile = $imgname.$image['file_ext'];
			$config['source_image'] = $temp_folder.$image['file_name'];
			$config['new_image'] = $thumb_folder.$imgfile ;
			$this->CI->load->library('image_lib',$config);
			if ($this->CI->image_lib->resize()):
				unlink($temp_folder.$image['file_name']);
				return $imgfile;
			endif;
		endif;
	}
	
	// @info	: UPLOAD FILE WITH AJAX
	// @params	: The second target folder 
	// @default	: Folder temp
	// @return	: STRING (File Name)
	function upload_ajax($folder='temp') {
		$target_folder	= 'storage/'.$folder.'/';
		
		$filebefore = $this->CI->input->post('dvd_gambar');
		$filename = basename($_FILES['userfile']['name']);
		
		//if(@is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			$ext = strrchr($filename,'.');			
			$rand = mktime();
			$md = md5($rand);
			$filename = substr($md,rand(0,strlen($md)-10),10).$ext;
			
			if ($filebefore != '')
				@unlink($target_folder.$filebefore);
			
			if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $target_folder.$filename)) {
				return $filename;	
			} 
		//}
	}
	
	function copy_image($image,$folder='dvd',$w=300,$h=300) {
		$target_folder	= 'storage/'.$folder.'/';
		$source_folder	= 'storage/temp/';
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source_folder.$image;
		$config['new_image'] = $target_folder.$image;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $w;
		$config['height'] = $h;
		$this->CI->image_lib->initialize($config); 
		if ($this->CI->image_lib->resize()):
			$this->CI->image_lib->clear();
			if ($folder!='thumb')
				$this->copy_image($image,'thumb',100,100);
			unlink($source_folder.$image);
			return $image;
		endif;
	}
	
	function delete_image($image,$relation = false) {
		if($relation)
			$target_folder = array('storage/dvd/','storage/thumb/','storage/temp/');
		else
			$target_folder[]	= 'storage/temp/';
		
		foreach ($target_folder as $target)
			if ($image)
				if (read_file($target.$image))
					unlink($target.$image);
		
		return $image;
	}
	
	function delete_all_images($folder='temp') {
		$target_folder	= 'storage/'.$folder.'/';
		$dir = directory_map($target_folder);
		foreach ($dir as $idx=>$file):
			unlink($target_folder.$file);
		endforeach;
	}

}
?>