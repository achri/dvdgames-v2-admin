<?

class Test extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('image_lib');
	}
	
	function index() {
		echo $this->copy_image('test.jpg');
	}
	
	function copy_image($image,$folder='dvd',$w=300,$h=300) {
		$target_folder	= $folder.'/';
		$source_folder	= 'temp/';
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source_folder.$image;
		$config['new_image'] = $target_folder.$image;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $w;
		$config['height'] = $h;		
		$this->image_lib->initialize($config); 
		if ($this->image_lib->resize()):
			$this->image_lib->clear();
			if ($folder!='thumb')
				$this->copy_image($image,'thumb',100,100);
			//unlink($source_folder.$image);
			return $image;
		endif;
	}
}