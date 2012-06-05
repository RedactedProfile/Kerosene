<?
class CMS extends BaseModel {
	
	const DISPLAY_PUBLISHED 	= 1;
	const DISPLAY_UNPUBLISHED 	= 2;
	const DISPLAY_ALL			= 3;
	const MODE_COUNT			= 1;
	const MODE_FETCH			= 2;
	const META_ALL				= 1;
	const META_TITLE			= 2;
	const META_KEYS				= 3;
	const META_DESCRIPTION		= 4;
	const META_NONE				= 5;
	
	public $id					= null;
	public $domain				= null;
	public $slug				= null;
	public $title				= null;
	public $content				= null;
	public $category			= null;
	public $meta_title			= null;
	public $meta_keys			= null;
	public $meta_description	= null;
	public $date_added			= null;
	public $date_updated		= null;
	public $display				= null;
	public $sort				= null;
	public $hits				= null;
	public $unique_hits			= null;
	public $homepage			= null;
	
	public function __construct($ref = null) {
		parent::__construct("pages");
		if($ref != null) {
			if(is_object($ref)) { // if were already an object, lets just do this and forget the extra call   ~k
				foreach($ref as $k=>$v) $this->{$k} = $v;
			} else {
				$this->fetch($ref);
			}
		}
	}
	
	public function setDomain($domain) {
		if(is_object($domain) && get_class($domain) == "Domain") $this->setDomain( $domain->getID() );
		elseif(intval($domain)) $this->domain = $domain;
		else throw new Exception("setDomain() requires a Domain class or integer");	
	}
		
	public function setSlug($str) {
		$this->db->select("slug")->from("pages")->where($this->SLUG_FIELD, $str)->where("domain", $this->getDomainID())->get();
		if($this->db->numrows()>0) $str = $str."_";
		$this->slug = $str; 
	}
	public function setTitle($str) { $this->title = $str; }
	public function setContent($str) { $this->content = $str; }
	public function setCategory($int) { $this->category = $int; }
	public function setMetaTitle($str) { $this->meta_title = $str; }
	public function setMetaKeys($str) { $this->meta_keys = $str; }
	public function setMetaDescription($str) { $this->meta_description = $str; }
	public function setDateAdded($str) { $this->date_added = $str; }
	public function setDateUpdated($str) { $this->date_updated = $str; }
	public function setDisplay($int) { $this->display = $int; }
	public function setSort($int) { $this->sort = $int; }
	public function setHits($int) { $this->hits = $int; }
	public function setUniqueHits($int) { $this->unique_hits = $int; }
	public function setHomepage($int) { $this->homepage = $int; }

	public function getID() { return $this->id; }
	public function getDomain() { return new Domain($this-getDomainID()); }
	public function getDomainID() { return $this->domain; }
	public function getSlug() { return $this->slug; }
	public function getTitle() { return $this->title; }
	public function getContent() { return $this->content; }
	public function getCategory() { return $this->category; }
	public function getMetaTitle() { return $this->meta_title; }
	public function getMetaKeys() { return $this->meta_keys; }
	public function getMetaDescription() { return $this->meta_description; }
	public function getDateAdded($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_added); }
	public function getDateUpdated($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_updated); }
	public function getDisplay() { return Tools::ReturnBinaryBool($this->display); }
	public function getSort() { return $this->sort; }
	public function getHits() { return $this->hits; }
	public function getUniqueHits() { return $this->unique_hits; }
	public function getHomepage() { return $this->homepage; }
	
	public function isHomepage() { return (($this->getHomepage())?true:false); }
	
	public function isComplete() {
		return ((
				  $this->getMetaTitle() != "" &&
				  $this->getMetaDescription() != "" &&
				  $this->getMetaKeys() != "" &&
				  $this->getContent() != ""
				) ?true:false);
	}
	
	public function getSubPages() {
		$pages = array();
		$fetch = $this->db->select("slug")->from("pages")->where("category", $this->getID())->get()->results();
		if($this->db->numRows() > 0) foreach($fetch as $page) $pages[] = $page;
		return $pages;
	}
	
	public function getParent() {
		if($this->getCategory() != 0) {
			return new CMS($this->getCategory());
		} else return false;
	}
	
	public function renderTitle($str = " :: ", $useSiteTitle = false) {
		$pieces = array();
		if($useSiteTitle) $pieces[] = Settings::GetSetting( Settings::SETTING_SITE_TITLE, "Default Site Name" );
		if($this->getCategory() != 0) $pieces[] = $this->getParent()->getTitle();
		$pieces[] = $this->getTitle();
		
		return implode($str, $pieces);
	}
	
	public function save() {
		$items = array(
			'slug',
			'domain',
			'title',
			'content',
			'category',
			'meta_title',
			'meta_keys',
			'meta_description',
			'display',
			'sort',
			'homepage'
		);
		
		foreach($items as $item) $this->db->set($item, $this->{$item});
		
		if($this->getID() == null) {	// new
			$this->db->insert("pages")->set("date_added", "NOW()", false)->set("date_updated", "NOW()", false);
		} else {						// update
			$this->db->update("pages")->where("id", $this->getID())->set("date_updated", "NOW()", false);
		}
		
		if($this->db->exec()) return true;
		else return false;
	}
	
	public function delete() {
		$this->db->delete("pages")->where("id", $this->getID());
		if($this->db->exec()) return true;
		else return false;
	}
	
	
	
	public static function HasChildren($category, $mode = self::DISPLAY_ALL) {
		global $db;
		switch($mode) {
			default:
			case self::DISPLAY_ALL:
				
				break;
			case self::DISPLAY_PUBLISHED:
				$db->where("display", 1);
				break;
			case self::DISPLAY_UNPUBLISHED:
				$db->where("display", 0);
				break;
		}
		$db->select("id")->from("pages")->where("category", $category)->get();
		if($db->numRows() > 0) return true;
		else return false;
	}
	
	public static function getChildren($category, $mode = self::DISPLAY_ALL) {
		global $db;
		switch($mode) {
			default:
			case self::DISPLAY_ALL:
		
				break;
			case self::DISPLAY_PUBLISHED:
				$db->where("display", 1);
				break;
			case self::DISPLAY_UNPUBLISHED:
				$db->where("display", 0);
				break;
		}
		$children = array();
		/*
		$fetch = $db->select("slug")->from("pages")->where("category", $category)->orderby("sort")->get()->results();
		if($db->numRows() > 0) foreach($fetch as $child) $children[] = new CMS($child->slug);
		*/
		// here we only need the one big sql call instead of the potential tens
		$fetch = $db->select("*")->from("pages")->where("category", $category)->orderby("sort")->get()->results();
		if($db->numRows() > 0) foreach($fetch as $child) $children[] = new CMS($child);
		
		return $children;
	}
	
	/**
	* This method is introduced as a replacement for if MySQL load is WAY too heavy, I understand that theres a LOT of stuff
	* going on with the standard object gathering way the rest of the site is built upon. So if it ever gets to that point, go
	* to /app/views/header.php and swap out getChildren in the nav with getChildrenLite. Should work roughly the same
	*/
	public static function getChildrenLite($category) {
	}
	
	public static function getCategories() {
		global $db;
		$pages = array();
		$fetch = $db->select("slug")->from("pages")->where("category", "0")->orderby("id")->get()->results();
		if($db->numRows() > 0) foreach($fetch as $page) $pages[] = $page;
		return $pages;
	}
	
	public static function countPages($category = null, $filter = CMS::DISPLAY_ALL) {
		global $db;
		switch($filter) {
			case CMS::DISPLAY_ALL:
				break;
			case CMS::DISPLAY_PUBLISHED:
				$db->where("published", 1);
				break;
			case CMS::DISPLAY_UNPUBLISHED:
				$db->where("published", 0);
				break;
		}
		
		
		
		$db->select("slug")->from("pages")->get()->results();
		return $db->numrows();
	}
	
	public static function getPagesFilter($category = null, $domain = null, $mode = CMS::MODE_FETCH, $fields = CMS::META_NONE, $filter = CMS::DISPLAY_ALL, $sort = "sort", $sortdir = "asc") {
		global $db;
		
		switch($filter) {
			case CMS::DISPLAY_ALL:
				break;
			case CMS::DISPLAY_PUBLISHED:
				$db->where("display", 1);
				break;
			case CMS::DISPLAY_UNPUBLISHED:
				$db->where("display", 0);
				break;
		}
		
		
		
		switch($fields) {
			case CMS::META_ALL:
				$db->where("meta_title", "")->orwhere("meta_description", "")->orwhere("meta_keys", "");
				break;
			case CMS::META_KEYS:
				$db->where("meta_keys", "");
				break;
			case CMS::META_TITLE:
				$db->where("meta_title", "");
				break;
			case CMS::META_DESCRIPTION:
				$db->where("meta_description", "");
				break;
			case CMS::META_NONE:
				break;
		}
		
		if($category != null) {
			$db->where("category", $category);
		} else {
			if(intval($category) == 0) // because 0 is "false", it doesnt check out normall
				$db->where("category", "0", "=");
		}
		
		if($domain != null) {
			$db->where("domain", $domain);
		} else {
			
		}
		
		if($sort) {
			$db->orderby($sort, (($sortdir == 1)?"asc":"desc"));
		} else {
			$db->orderby("id", "asc");
		}
		
		//$fetch = $db->select("id")->from("pages")->get()->results();
		$fetch = $db->select("*")->from("pages")->get()->results(); 	// ~Optimized Object Instantiation Calling   ~k
		
		switch($mode) {
			case CMS::MODE_FETCH:
				$pages = array();
				
				//if($db->numrows()>0) foreach($fetch as $page) $pages[] = new CMS($page->id);
				if($db->numrows()>0) foreach($fetch as $page) $pages[] = new CMS($page);   // ~Optimized Object Instantiation Calling   ~k

				return $pages;
				
				break;
			case CMS::MODE_COUNT:
				
				
				return $db->numrows();
				
				break;
		}
		
	}
	
	
	public static function IncrementHits($page) {
		if(is_object($page) && get_class($page) == "CMS") {} 
		else $page = new CMS($page);
		
		$page->setHits( $page->getHits() + 1 );
		$page->save();
	}
	
	public static function IncrementUniqueHits($page) {
		if(is_object($page) && get_class($page) == "CMS") {
		}
		else $page = new CMS($page);
	
		$page->setUniqueHits( $page->getUniqueHits() + 1 );
		$page->save();
	}
	
	public static function MakeHompage($pageID, $domain = null) {
		if($domain == null) $domain = Session::data("currentDomain");
		if(!$domain) return false;
		
		foreach(CMS::getPagesFilter(0, $domain, CMS::MODE_FETCH) as $page) {
			$page->setHomepage(0);
			if($page->getID() == $pageID) $page->setHomepage(1);
			if(!$page->save()) return false;
		}
		return true;
	}
	
	public static function GetDomainHomepage($domain) {
		global $db;
		$fetch = $db->select("id")->from("pages")->where("domain", $domain)->where("homepage", 1)->get()->result();
		if($db->numrows() > 0) {
			return new CMS($fetch->id);
		} else {
			$fetch = $db->select("id")->from("pages")->where("domain", $domain)->orderby("sort")->get()->result();
			if($db->numrows()>0) {
				return new CMS($fetch->id);
			} else return false;
		}
	}
	
}

