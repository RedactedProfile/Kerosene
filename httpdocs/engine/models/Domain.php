<?php
class Domain extends BaseModel {
	
	const TABLE					= "domains";
	
	const SORT_ID				= 1;
	const SORT_NAME				= 2;
	const SORT_DOMAIN			= 3;
	const SORT_ACTIVE			= 4;
	const SORT_DATE				= 5;
	
	public $id			= null;
	public $name		= null;
	public $domain		= null;
	public $active		= null;
	public $date_added	= null;
	
	protected $SLUG_FIELD = "name";
	
	public function __construct($ref = null) {
		parent::__construct(self::TABLE);
		if($ref != null) $this->fetch($ref);
	}
	
	public function setName($str) { $this->name = $str; }
	public function setDomain($str) { $this->domain = $str; }
	public function setActive($bool) { $this->active = Tools::ReturnBinaryBool($bool); }
	
	public function getID() { return $this->id; }
	public function getName() { return $this->name; }
	public function getDomain() { return $this->domain; }
	public function getActive() { return $this->active; }
	public function isActive() { return Tools::ReturnBinaryBool($this->getActive()); }
	public function getDateAdded($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_added); }
	
	public function delete() {
		if($this->db->delete(self::TABLE)->where($this->ID_FIELD, $this->getID())->exec()) return true;
		else return false;
	}
	
	public function save() {
		
		$this->db->set("name", $this->getName())->set("domain", $this->getDomain())->set("active", $this->getActive());
		
		if($this->getID()) {
			$this->db->update(self::TABLE)->where($this->ID_FIELD, $this->getID());
		} else {
			$this->db->insert(self::TABLE)->set("date_added", "NOW()", false);
		}
		
		$this->db->exec();
		
		if($this->db->affectedRows()>0) return true;
		else return false;
		
	}
	
	public static function SetCurrentDomain($domain = null, $createDomainIfDoesntExist = false) {
		global $db;

		$domain = explode(".", preg_replace(
				array(
					"/http\:\/\//",
					"/www\./"
				),
				array(
					"", ""
				),
				(($domain)?$domain:$_SERVER['HTTP_HOST'])
			)
		);
		$domainPieces = $domain;
		$domain = new Domain($domain[0]);
		if(!$domain->getID()) {
			if($createDomainIfDoesntExist){
				if(!$domain->getID()) {
					// create domain
					$domain->setName($domainPieces[0]);
					$domain->setDomain($domainPieces[1]);
					$domain->setActive(1);
					$domain->save();
				}
			} else {
				return false;
			}
		}
		/*
		} else {
			$domain = new Domain($domain);
			if(!$domain->getID()) $fallback = true;
		}
		*/
		
		
		Session::data("currentDomain", $domain->getID() );
	}
	
	public static function GetDomains($filter = Filter::ALL, $sort = self::SORT_ID, $sortdir = Sort::ASC) {
		global $db;
		$domains = array();
		$db->select("id")->from(self::TABLE);
		switch($filter) {
			case Filter::ACTIVE:
				$db->where("active", 1);
				break;
			case Filter::INACTIVE:
				$db->where("active", 0);
				break;
		}
		
		switch($sortdir) {
			case Sort::ASC:
				$sortdir = "asc";
				break;
			case Sort::DESC:
				$sortdir = "desc";
				break;
		}
		
		switch($sort) {
			case self::SORT_ID:
				$db->orderby("id", $sortdir);
				break;
			case self::SORT_NAME:
				$db->orderby("name", $sortdir);
				break;
			case self::SORT_DOMAIN:
				$db->orderby("domain", $sortdir);
				break;
			case self::SORT_ACTIVE:
				$db->orderby("active", $sortdir);
				break;
			case self::SORT_DATE:
				$db->orderby("date_added", $sortdir);
				break;
		}
		
		$fetch = $db->get()->results();
		if($db->numrows() > 0) foreach($fetch as $domain) $domains[] = new Domain($domain->id);
		
		return $domains;
	}
	
}