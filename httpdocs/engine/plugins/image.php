<?php
class ImageConfig {	
	
	public $type = "jpg";
	public $maxWidth = 3000;
	public $maxHeight = 3000;

}
class Image {
	
	public $image	= null;
	public $config	= null;
	
	public function __construct($image = null, $config = null) {
		if($image != null) $this->addImage($image);
		if($config != null) $this->config = $config;
		else $this->config = new ImageConfig();
	}
	
	public function addImage($img) {
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)) $this->image = $img;
	}
	
	
	public static function resize($img, $config) {
		$img = new Image($img, $config);
		// these static functions are macro shortcuts to doing standard type work with images
	}
	
}