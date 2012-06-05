<?
class Account extends BaseModel {

	const SITE_DEVELOPER		= 1;
	const SUPER_ADMINISTRATOR	= 1;
	const SITE_OWNER			= 2;
	const ADMINISTRATOR			= 2;
	const USER					= 3;
	
	public $id			= null;
	public $login		= null;
	public $display		= null;
	public $password	= null;
	public $last_login	= null;
	public $level		= null;

	public function __construct($ref = null) {
		parent::__construct("accounts");
		
		if($ref != null) $this->fetch($ref);
	}
	
	
	public function setID($int) { $this->id = $this->clean($int); }
	public function setLogin($string) { $this->login = $this->clean($string); }
	public function setDisplay($string) { $this->display = $this->clean($string); }
	public function setPassword($string) { $this->password = $this->clean($string); }
	public function setLastLogin($date) { $this->last_login = $date; }
	public function setLevel($int = self::USER) { $this->level = $int; }
	
	public function getID() { return $this->id; }
	public function getLogin() { return $this->login; }
	public function getDisplay() { return $this->display; }
	public function getPassword() { return $this->password; }
	public function getLastLogin() { return $this->last_login; }
	public function getLevel() { return $this->level; }
	
	public function get($element) { // pretty insecure method but whatevs
		return $this->{$element};
	}
	
	public function save() {
		
	}
	
	/**
	* to use, instantiate a new Account, supply the login and password
	*/
	public function login() {
		$fetch = $this->db->select("id")->from($this->table)->where("login", $this->getLogin())->where("password", sha1($this->getPassword()))->get()->result();
		if($this->db->numRows() > 0) {
			Session::data("me", serialize(new Account($fetch->id)));
			return true;
		} else return false;
	}
	
	public static function GetData($element = null) {
		if(Session::data("me")) {
			$data = unserialize(Session::data("me"));
			if($element == null) return $data;
			else return $data->get($element);
		} else return null;
	}
	
	public static function kill() {
		Session::kill("me");
	}
	
	
	
	

}
