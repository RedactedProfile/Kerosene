<?
class ImageManipulationMeta {
	const RESIZE	= 1;
	const RESAMPLE	= 2;
	
	public $type	= null;
}
class Upload {
	
	const MODE_DEFAULT			= 1;
	const MODE_MANIPULATE		= 2;
	
	public $name 				= null;
	public $tmpName				= null;
	public $fileName 			= null;
	public $fileExt 			= null;
	public $fileType 			= null;
	public $error 				= null;
	public $size 				= null;
	public $finalPath 			= "/";
	public $force				= false;
	
	private $mode				= null;
	private $manipulateMeta		= null;
	
	public function __construct($filearr = null) {
		if($filearr != null) $this->parse($filearr);
		
		$this->mode = self::MODE_DEFAULT;
	}
	
	public function parse($filearr) {
		//var_dump($filearr);
		$arr = (object)$filearr;
		$this->name = $arr->name;
		$this->tmpName = $arr->tmp_name;
		$this->fileType = $arr->type;
		$this->error = $arr->error;
		$this->size = $arr->size;
	}
	
	public function getName() { return $this->name; }
	public function getTmpName() { return $this->tmpName; }
	public function getError() { return $this->error; }
	public function getSize() { return $this->size; }
	public function getType() { return $this->type; }
	public function getPath() { return $this->finalPath; }
	
	public function getFileName() { return $this->fileName; }
	
	public function setName($str) { $this->fileName = $str; }
	public function setPath($str) { $this->finalPath = $str; }
	
	
	
	// manipulation 
	public function resize($width, $height, $quality = 100, $keepRatio = false, $force = false) {
		$this->mode = self::MODE_MANIPULATE;
		$this->manipulateMeta = new ImageManipulationMeta();
		$this->manipulateMeta->type = ImageManipulationMeta::RESIZE;
		$this->manipulateMeta->width = $width;
		$this->manipulateMeta->height = $height;
		$this->manipulateMeta->quality = $quality;
		$this->manipulateMeta->keepRatio = $keepRatio;
		$this->manipulateMeta->forceSize = $force;		
	}
	
	
	
	/**
	* This will force checking if the directory exists, if it doesnt, will create it, and set permissions automatically
	*/
	public function forcePath() { $this->force = true; }
	
	public function parseType() {
		$type = "unknown";
		switch($this->fileType) {
			// Images
			case "image/png":
				$type = "png";
				break;
			case "image/jpg":
			case "image/jpeg":
				$type = "jpg";
				break;
			case "image/gif":
				$type = "gif";
				break;
			default:
				$type = explode(".", $this->getName());
				$type = array_pop($type);
				break;
		}
		
		return $type;
	}
	public function isImage() {
		switch($this->parseType()) {
			case "png":
			case "jpg":
			case "gif":
				return true;
				break;
			default: 
				return false;
				break;
		}
	}
	
	public function isDoc() {
	}
	
	public function isPDF() {
	}
	
	public function exec() {
		if($this->force) {
			$currentPath = "";
			$folders = explode("/", $this->getPath());
			chdir($_SERVER['DOCUMENT_ROOT']."/");
			$currentPath = $_SERVER['DOCUMENT_ROOT']."/";
			// check each directory exists
			foreach($folders as $folder) {
				if(trim($folder) != '') {
					if(!@chdir($folder)) { // failed to get to the folder, make it
						mkdir($folder);
						chmod($folder, 0777);
						if(!chdir($folder)) {
							die("Somethings going terribly wrong");
						} else {
							$currentPath .= $folder."/";
						}
					} else {
						$currentPath .= $folder."/";
					}
				}
			}
			//echo $currentPath;
		}
		
		switch($this->mode) {
			case self::MODE_DEFAULT:
				return move_uploaded_file($this->getTmpName(), $_SERVER['DOCUMENT_ROOT']."/".$this->getPath().$this->getFileName());
				break;
			case self::MODE_MANIPULATE:
				
				$image = new Imagick( $this->getTmpName() );
				switch($this->manipulateMeta->type) {
					
					case ImageManipulationMeta::RESIZE:
						
						$image->setcompressionquality($this->manipulateMeta->quality);
						
						// if forceSize is off (as in only when the image is larger) and if either width or height is larger than the requested width and height
						if(
							(
								$this->manipulateMeta->forceSize == false && 
								(
									$image->getimagewidth() > $this->manipulateMeta->width ||
									$image->getimageheight() > $this->manipulateMeta->height
								)
							) ||
							(
								$this->manipulateMeta->forceSize == true
							)
						) {
							$image->resizeimage($this->manipulateMeta->width, $this->manipulateMeta->height, Imagick::FILTER_GAUSSIAN, 0.1, $this->manipulateMeta->keepRatio);
						}
						
						
						
						break;
					case ImageManipulationMeta::RESAMPLE:
						
						break;
					
					
				}
				
				return $image->writeimage( $_SERVER['DOCUMENT_ROOT']."/".$this->getPath().$this->getFileName() );
				
				
				break;
		}
		
	}
	
	public static function getFiles($index = null) {
		$files = array();
		
		if($index == null) foreach($_FILES as $k=>$file) $files[$k] = $file;
		else return ((isset($_FILES[$index]))?$_FILES[$index]:false);
		
		return $files;
	}
	
	public static function getBatchFiles($name) {
		$files = array();
		
		foreach($_FILES[$name]['name'] as $k=>$v) {
			$file = array();
			$file['name'] = $_FILES[$name]['name'][$k];
			$file['type'] = $_FILES[$name]['type'][$k];
			$file['tmp_name'] = $_FILES[$name]['tmp_name'][$k];
			$file['error'] = $_FILES[$name]['error'][$k];
			$file['size'] = $_FILES[$name]['size'][$k];
			
			$files[] = new Upload($file);
		}
		
		return $files;
	}
}