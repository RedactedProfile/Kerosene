<?php
class Slider_Setting {		// struct
	
	public $is_on					= "";
	public $transition				= "";
	public $animation_speed			= "";
	public $transition_speed		= "";
	public $is_slide_nav_on			= "";
	public $caption_transition		= "";
	public $caption_animation_speed	= "";
	public $is_slide_nav_bullet_on	= "";
	public $width					= "";
	public $height					= "";
	
	public function __construct() {
		$this->setIsOn(true);
		$this->setTransition("fade");
		$this->setAnimationSpeed(800);
		$this->setTransitionSpeed(800);
		$this->setIsSlideNavOn(true);
		$this->setCaptionTransition("fade");
		$this->setCaptionAnimationSpeed(800);
		$this->setIsSlideNavBulletOn(true);
		$this->setWidth(978);
		$this->setHeight(400);
	}
	
	public function isOn() { return (($this->is_on == 1)?true:false); }
	public function getTransition() { return $this->transition; }
	public function getAnimationSpeed() { return $this->animation_speed; }
	public function getTransitionSpeed() { return $this->transition_speed; }
	public function isSlideNavOn() { return (($this->is_slide_nav_on == 1)?true:false); }
	public function getCaptionTransition() { return $this->caption_transition; }
	public function getCaptionAnimationSpeed() { return $this->caption_animation_speed; }
	public function isSlideNavBulletOn() { return (($this->is_slide_nav_bullet_on == 1)?true:false); }
	public function getWidth() { return $this->width; }
	public function getHeight(){ return $this->height; }
	
	public function setIsOn($bool) { $this->is_on = Tools::ReturnBinaryBool($bool); }
	public function setTransition($str) { $this->transition = $str; }
	public function setAnimationSpeed($int) { $this->animation_speed = $int; }
	public function setTransitionSpeed($int) { $this->transition_speed = $int; }
	public function setIsSlideNavOn($bool) { $this->is_slide_nav_on = Tools::ReturnBinaryBool($bool); }
	public function setCaptionTransition($str) { $this->caption_transition = $str; }
	public function setCaptionAnimationSpeed($int) { $this->caption_animation_speed = $int; }
	public function setIsSlideNavBulletOn($bool) { $this->is_slide_nav_bullet_on = Tools::ReturnBinaryBool($bool); }
	public function setWidth($int) { $this->width = $int; }
	public function setHeight($int) { $this->height = $int; }
	
	
}
class Slider extends BaseModel {
	
	const FILTER_ALL		= 1;
	const FILTER_PUBLISHED	= 2;
	
	public $id				= null;
	public $img				= null;
	public $caption			= null;
	public $uri				= null;
	public $target			= null;
	public $sort			= null;
	public $published		= null;
	public $date_added		= null;
	public $date_updated	= null;
	
	public function __construct($id = null) {
		parent::__construct("slider");
		if($id != null) $this->fetch($id);
	}
	
	public function getID() { return $this->id; }
	public function getImage() { return $this->img; }
	public function getThumbnail($width = 150, $height = 150) { 
		return "/thumb.php?path=slider|".$this->getImage()."&width=".$width."&height=".$height;
	}
	public function getCaption() { return $this->caption; }
	public function getURI() { return $this->uri; }
	public function getTarget() { return $this->target; }
	public function getSort() { return $this->sort; }
	public function getPublished() { return (($this->getPublishedRaw() == 1)?true:false); }
	public function getPublishedRaw() { return $this->published; }
	public function getDateAdded($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_added); }
	public function getDateUpdated($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_updated); }
	
	public function setImage($str) { $this->img = $str; }
	public function setCaption($str) { $this->caption = $str; } 
	public function setURI($str) { $this->uri = $str; }
	public function setTarget($str) { $this->target = $str; }
	public function setSort($int) { $this->sort = $int; }
	public function setPublished($bool) { $this->published = Tools::ReturnBinaryBool($bool); }
	
	public function save() {
		$nextHighestSort = Slider::getNextHighestSort();
		
		$data = array( 'img', 'caption', 'target', 'uri', 'published' );
		foreach($data as $d) $this->db->set($d, $this->{$d});
		$this->db->set('date_updated', 'NOW()', false);
		if($this->getID() == null) $this->db->insert("slider")->set("sort", $nextHighestSort)->set("date_added", 'NOW()', false);
		else $this->db->update("slider")->set("sort", $this->getSort())->where("id", $this->getID());
		
		return $this->db->exec();
	}
	
	public function delete() {
		return $this->db->delete("slider")->where("id", $this->getID())->exec();
	}
	
	
	public static function getSlides($filter = self::FILTER_ALL) {
		global $db;
		$slides = array();
		switch($filter) {
			case self::FILTER_ALL:
				break;
			case self::FILTER_PUBLISHED:
				$db->where("published", 1);
				break;
		}
		$fetch = $db->select("id")->from("slider")->orderby("sort")->get()->results();
		if($db->numrows() > 0) foreach($fetch as $slide) $slides[] = new Slider($slide->id);
		
		return $slides;
	}
	
	public static function getNextHighestSort() {
		global $db;
		$sort = $db->select("sort")->from("slider")->orderby("sort", "desc")->limit(1)->get()->result();
		return $sort->sort + 1;
	}
	
}