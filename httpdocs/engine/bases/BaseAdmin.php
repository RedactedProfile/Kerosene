<?
class BaseAdmin extends BasePage {
	
	public function __construct() {
		parent::__construct();
		
		$this->data['header'] = array(
			'title'=> array("Applewood Administration System"),
			'pages'=> array(	// This is just for page titles
				''=>"Home",
				'cart'=>"Cart System",
				'domains'=>"Manage Domains",
				'secure'=>"Please Log In",
				'pages'=>"Page Management",
				'gallery'=>"Gallery Management",
				'blog'=>"Blog Management",
				'slideshow'=>"Slideshow",
				'map'=>"Map Editor",
				'settings'=>"Site Settings",
				'ajax'=>"Ajax"
			)
		);
		$this->data['uri'] = $this->uri;
	}
	
	public static function parseTitle($arr) {
		return implode(" :: ", $arr);
	}
}