<?
class DBConfiguration {
	public $connect	= false;
	public $condition = false;
	public $config	= array();
}
class Version {
	public $name			= "";
	public $version			= "";
	public function __construct($name, $version) {
		$this->name =$name;
		$this->version = $version;
	}
	public function getName() { return $this->name; }
	public function getVersion() { return "v".$this->version; }
}
class Configuration {
	public $DefaultController = null;
	public $db				  = null;
	public $Bases			  = array();
	public $Controllers		  = array();
	public $Models			  = array();
	public $Assistants		  = array();
	public $Plugins			  = array();
	public $Views			  = array();
	public $SecurePages		  = array();
	public $DocRoot			  = null;
	public $Root			  = null;
	public $Engine			  = null;
	
	public function __construct() {
		$this->db = new DBConfiguration();
		$this->Engine = new Version("Kerosene", "1.0.0");
	}
	
	public static function checkInstall() {
		
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/app/controllers/install.php")) {
			if(!preg_match("/\/install/", $_SERVER['REQUEST_URI'])) {
				header("location: /install");
			}
		}
		
	}
}