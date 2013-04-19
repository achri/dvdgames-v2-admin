<?php if ( ! defined('BASEPATH')) exit('cant access');

/*
* creator Achri 2011
* JAVASCRIPT AND CSS MINIFY GENERATOR
* ==========================================
* > must run one time, it make browser load slowly
* - need database to compare file size; note: i think nicely usage temp compare database :D
*/

// include dean javascript packer for php http://dean.edwards.name/
include_once(APPPATH.'libraries/packer/class.JavaScriptPacker.php');
// include cssmin for php http://code.google.com/p/cssmin/
include_once(APPPATH.'libraries/packer/cssmin-v3.0.1.php');
include_once(APPPATH.'libraries/packer/CssUrlPrefixMinifierPlugin.php'); // url fix for image and all

class Packer_lib {
	protected $CI;
	protected $minifyPath;
	protected $jsPath;
	protected $cssPath;
	protected $fileName;
	protected $newName;
	protected $realPath;
	protected $newPath;
	protected $newMinify; // full link of new minify
	protected $newPacked;	// non hostname of new minify
	protected $sourceMod; // the time of file modiff
		
	protected $type = array(0=>'.packed.js',1=>'.packed.css'); // packed name
	protected $pettern = "/.min.|-packed|.custom.|-en./"; // pattern outside process
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->minifyPath = $this->CI->config->item('url_packed');	// get config of minify path
		$this->jsPath = $this->minifyPath['js'];	// set new minify js path
		$this->cssPath = $this->minifyPath['css']; // set new minify css path
	}
	
	// CREATE MINIFY OF CSS OR JS
	protected function _packIt($css)
	{
		// check the folder exist
		$jsFolder = str_replace(base_url(),'',substr($this->jsPath,0,strlen($this->jsPath)-1)); 
		if(FALSE === is_dir($jsFolder))
			@mkdir($jsFolder);
			
		$cssFolder = str_replace(base_url(),'',substr($this->cssPath,0,strlen($this->cssPath)-1)); 
		if(FALSE === is_dir($cssFolder))
			@mkdir($cssFolder);
		
		$content = file_get_contents($this->realPath); // get file content
		// packer for css by cssmin
		if ($css) {
			$packer = new CssMinifier($content,array(), array(
				"UrlPrefix" => array( "BaseUrl" => 'asset/css/' )
				)
			);
			$packed = $packer->getMinified();
		} 
		// packer for javascript by dean packer
		else {
			$packer = new JavaScriptPacker($content, 'Normal', true, false);
			$packed = $packer->pack();
		}
		
		file_put_contents($this->newPacked,$packed); // create a new packed to temp folder
	}
	
	// PROCESS TO REMOVE THE OLD MINIFY ON PATH
	protected function _delIt()
	{
		$setDir  = str_replace(base_url(),'',$this->newPath); // parse the asset minify without host name
		$arrFile = explode('.',$this->newName);	// explode newname into array
		$setName = implode('.',array_splice($arrFile,0,(count($arrFile) - 3))); // erase the {time}.packed.js on array and implode the new array
		$findFile = glob($setDir.'{'.$setName.'}*',GLOB_BRACE); // find file like set name on folder
		@chmod($findFile,0777);	// change the file to full access
		if (@unlink($findFile))	// remove the old file
			return TRUE;
	}
		
	// PROCESS TO CHECK DIFFERENT TIME ON MINIFY AND SOURCE FILE
	protected function _checkIt($css)
	{		
		if (FALSE === file_exists($this->newPacked)) 
		{
			$this->_packIt($css);
			log_message('dvd','minify created : '.$this->newPacked);
		}
		else
		{
			$getMod = array_reverse(explode('.',$this->newPacked));
			$fileMod = $getMod[2];
			if ($fileMod <> $this->sourceMod) {
				if ($this->_delIt()) // delete the old minify
				{	
					$this->_packIt($css);	// create the new one
					log_message('dvd','minify modifed : '.$this->newPacked);
				} else
					log_message('dvd','minify error modifed : '.$this->newPacked);
			}			
		}
		return $this->newMinify;
	}
	
	// PREPARE TO PARSE PATH AND FILENAME
	protected function _parseIt($path,$newPath,$css = FALSE)
	{
		$this->realPath = $path; // keep old name
		$this->newPath = $newPath; // set to new path for minify
		$arrPath = explode('/',$path); // parse real filename
		$this->fileName = $arrPath[count($arrPath) - 1]; // set real filename
		
		$this->sourceMod = $this->_fileModRemote($path); // get the time modiffed from source
		$setTime = '.'.$this->sourceMod;	// set time on name
		if ($css)
			$setTime = $this->sourceMod;
		
		$this->newName = substr($this->fileName,0,strlen($this->fileName) - 3).$setTime.$this->type[$css]; // parse packedname
		$this->newPacked = str_replace(base_url(),'',$this->newPath).$this->newName; // new packed filename non http
		
		$this->newMinify = $this->newPath.$this->newName; // new packed filename full url
		
		return $this->_checkIt($css); // return the new path or the old path
	}
	
	// PREPARE TO POPULATE PATH FROM HELPER TAG
	public function generate($path,$packPath,$css = FALSE)
	{	
		$return = array();
		if(is_array($path))
			foreach ($path as $getPath)
				$return[] = $this->_parseIt($getPath,$packPath,$css);
		else
			$return[] = $this->_parseIt($path,$packPath,$css);
		return $return;
	}
	
	// TRY TO GET FILE MODIFIED VIA CURL
	protected function _fileModRemote($path)
	{
		$curl = curl_init($path);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FILETIME, true);

		$result = curl_exec($curl);
		if ($result === false) {
				die (curl_error($curl)); 
		}
	
		return curl_getinfo($curl, CURLINFO_FILETIME);	
	}
	
	/*
	// for testing current time of file
	protected function _fileMod($filePath)
	{

    $time = filemtime($filePath);

    $isDST = (date('I', $time) == 1);
    $systemDST = (date('I') == 1);

    $adjustment = 0;

    if($isDST == false && $systemDST == true)
        $adjustment = 3600;
   
    else if($isDST == true && $systemDST == false)
        $adjustment = -3600;

    else
        $adjustment = 0;

    return ($time + $adjustment);
	} 
	
	// get remote file last modification date (returns unix timestamp)
	protected function _fileModRemote( $uri )
	{
    // default
    $unixtime = 0;
   
    $fp = fopen( $uri, "r" );
    if( !$fp ) {return;}
   
    $MetaData = stream_get_meta_data( $fp );
       
    foreach( $MetaData['wrapper_data'] as $response )
    {
        // case: redirection
        if( substr( strtolower($response), 0, 10 ) == 'location: ' )
        {
            $newUri = substr( $response, 10 );
            fclose( $fp );
            return $this->_fileModRemote( $newUri );
        }
        // case: last-modified
        elseif( substr( strtolower($response), 0, 15 ) == 'last-modified: ' )
        {
            $unixtime = strtotime( substr($response, 15) );
            break;
        }
    }
    fclose( $fp );
    return $unixtime;
	}
	
	*/

}