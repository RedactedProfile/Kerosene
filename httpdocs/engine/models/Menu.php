<?php
class MenuItem extends BaseModel {
	public $id		= null;
	public $menu	= null;
	public $parent	= null;
	public $target	= null;
	public $label	= null;
	public $uri		= null;
	public $homepage= null;
	public $sort	= null;
	public $classes	= null;
	
	public function __construct($ref = null) {
		parent::__construct("menus.links");
		if($ref != null) {
			if(is_object($ref)) {
				foreach($ref as $k=>$v) $this->{$k} = $v;
			} else $this->fetch($ref);
		}
	}
	
	public function getID() { return $this->id; }
	public function getMenu() { return new Menu($this->getMenuID()); }
	public function getMenuID() { return $this->menu; }
	public function getParent() { return new MenuItem($this->getParentID()); }
	public function getParentID() { return $this->parent; }
	public function getTarget() { return $this->target; }
	public function getLabel() { return $this->label; }
	public function getURI() { return $this->uri; }
	public function getSort() { return $this->sort; }
	public function getHomepage() { return $this->homepage; }
	public function isHomepage() { return (($this->getHomepage())?true:false); }
	public function getClasses() { return $this->classes; }
	
	
	public function setMenu($menu) {
		if(Tools::CheckClass($menu, "Menu")) $menu = $menu->getID();
		$this->menu = $menu;
		return $this;
	}
	public function setParent($parent) {
		if(Tools::CheckClass($parent, "MenuItem")) $parent = $parent->getID();
		$this->parent = $parent;
		return $this;
	}
	public function setTarget($int) { 
		switch($int) {
			case Target::_NEW:
				$this->target = "_blank";
				break;
			case Target::_SAME:
				$this->target = "_self";
				break;
		}
		return $this;
	}
	public function setLabel($str) { $this->label = $str; return $this; }
	public function setURI($str) { $this->uri = $str; return $this; }
	public function setSort($int) { $this->sort = $int; return $this; }
	public function setClasses($str) { $this->classes = $str; return $this; }
	
	public function delete() {
		return $this->db->delete($this->table)->where($this->ID_FIELD, $this->getID())->exec();
	}
	
	public function save() {
		$this->db->set("menu", $this->getMenuID())
				 ->set("parent", $this->getParentID())
				 ->set("target", $this->getTarget())
				 ->set("label", $this->getLabel())
				 ->set("uri", $this->getURI())
				 ->set("sort", $this->getSort())
				 ->set("classes", $this->getClasses())
		;
		if($this->getID()) $this->db->update($this->table)->where($this->ID_FIELD, $this->getID());
		else $this->db->insert($this->table);
		
		return $this->db->exec();
	}
	
	public static function HasChildren($menuItem) {
		global $db;
		if(Tools::CheckClass($menuItem, "MenuItem")) $menuItem = $menuItem->getID();
		$db->select("id")->from("menus.links")->where("parent", $menuItem)->exec();
		return $db->numrows();
	}
	
	public static function GetChildren($menuItem) {
		global $db;
		$children = array();
		if(Tools::CheckClass($menuItem, "MenuItem")) $menuItem = $menuItem->getID();
		$fetch = $db->select("*")->from("menus.links")->where("parent", $menuItem)->orderby("sort")->get()->results();
		if($db->numrows()>0) foreach($fetch as $child) $children[] = new MenuItem($child);
		return $children;
	}
	
}
class Menu extends BaseModel {
	
	/**
	 * Migrate existing menu items to another menu
	 */
	const MODE_DELETE_MIGRATE	= 1;
	/**
	 * Delete all menu items as well
	 */
	const MODE_DELETE_WIPE		= 2;
	
	public $id			= null;
	public $domain		= null;
	public $name		= null;
	public $identifier	= null;
	public $date_added	= null;
	
	protected $SLUG_FIELD = "identifier";
	
	public function __construct($ref = null) {
		parent::__construct("menus");
		if($ref != null) {
			if(is_object($ref)) {
				foreach($ref as $k=>$v) $this->{$k} = $v;
			} else $this->fetch($ref);
		}
	}
	
	
	public function getID() { return $this->id; }
	public function getDomain() { return new Domain($this->getDomainID()); }
	public function getDomainID() { return $this->domain; }
	public function getName() { return $this->name; }
	public function getIdentifier() { return $this->identifier; }
	public function getDateAdded() { return $this->date_added; }
	
	public function setDomain($domain) { if(Tools::CheckClass($domain, "Domain")) $this->domain = $domain->getID(); else $this->domain = $domain; return $this; }
	public function setName($str) { $this->name = $str; return $this; }
	public function setIdentifier($str) { $this->identifier = $str; return $this; }
	
	public function delete( $mode = self::MODE_DELETE_MIGRATE, $menu = 0 ) {
		// trying to figure out where to migrate menu items to
		if(is_object($menu) && Tools::CheckClass($menu, "Menu")) $menu = $menu->getID();
		
		switch($mode) {
			case self::MODE_DELETE_MIGRATE:
				
				foreach(Menu::GetMenuItems($this->getID()) as $item) {
					$item->setMenu($menu);
					$item->save();
				}
				
				break;
			case self::MODE_DELETE_WIPE:
				
				foreach(Menu::GetMenuItems($this->getID()) as $item) {
					$item->delete();
				}
				
				break;
		}
		return $this->db->delete($this->table)->where($this->ID_FIELD, $this->getID())->exec();	
		
	}
	
	public function save() {
		$this->db->set("name", $this->getName())->set("identifier", $this->getIdentifier())->set("domain", $this->getDomainID());
		if($this->getID()) $this->db->update($this->table)->where($this->ID_FIELD, $this->getID());
		else $this->db->insert($this->table)->set("date_added", "NOW()", false);
		
		return $this->db->exec();
	}
	
	
	public static function GetMenuItems($menu) {
		global $db;
		if(is_object($menu) && Tools::CheckClass($menu, "Menu")) $menu = $menu->getID();
		$items = array();
		$fetch = $db->select("*")->from("menus.links")->where("menu", $menu)->get()->results();
		if($db->numrows()>0) foreach($fetch as $item) $items[] = new MenuItem($item);
		return $items;
	}
	
	
	public static function getMenus($domain) {
		global $db;
		if(Tools::CheckClass($domain, "Domain")) $domain = $domain->getID();
		$menus = array();
		$fetch = $db->select("*")->from("menus")->where("domain", $domain)->orderby("name")->get()->results();
		if($db->numrows()>0) foreach($fetch as $menu) $menus[] = new Menu($menu);
		return $menus;
	}
	
}