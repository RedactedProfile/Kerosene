<?php
class GalleryPhoto extends BaseModel { // individual image
	
	public $id			= null;
	public $album		= null;
	public $image		= null;
	public $caption		= null;
	public $sort		= null;
	public $published	= null;
	public $date_added	= null;
	
	public function __construct($id = null) {
		parent::__construct("gallery_photos");
		if($id != null) $this->fetch($id);
		else $this->setImage("noimage.png");
	}
	
	public function setAlbum($int) { $this->album = $int; }
	public function setImage($str) { $this->image = $str; }
	public function setCaption($str) { $this->caption = $str; }
	public function setSort($int) { $this->sort = $int; }
	public function setPublished($bool) { $this->published = Tools::ReturnBinaryBool($bool); }
	
	public function getID() { return $this->id; }
	public function getAlbum() { return new Album($this->getAlbumID()); }
	public function getAlbumID() { return $this->album; }
	public function getImage() { return $this->image; }
	public function getCaption() { return $this->caption; }
	public function getSort() { return $this->sort; }
	public function isPublished() { return (($this->published == 1)?true:false); }
	public function getDateAdded($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_added); }
	
	/**
	* Genreates a realtime thumbnail embedded in an iframe
	*/
	public function getThumbnail($width = 150, $height = 150) {
		return "/thumb.php?path=gallery|".str_replace("/", "|", $this->getAlbum()->getSlug()."/".$this->getImage())."&width=".$width."&height=".$height;
	}
	
	public function save() {
		$nextHighestSort = GalleryPhoto::getNextHighestSort();
		$fields = array( 'album', 'image', 'caption', 'published' );
		foreach($fields as $field) $this->db->set($field, $this->{$field});

		if($this->getID() == null) $this->db->insert("gallery_photos")->set("sort", $nextHighestSort)->set("date_added", 'NOW()', false);
		else $this->db->update("gallery_photos")->set("sort", $this->getSort())->where("id", $this->getID());
	
		return $this->db->exec();
	}
	
	public function delete() {
		return $this->db->delete("gallery_photos")->where("id", $this->getID())->exec();
	}
	
	
	
	public static function getNextHighestSort() {
		global $db;
		$sort = $db->select("sort")->from("gallery_photos")->orderby("sort", "desc")->limit(1)->get()->result();
		return $sort->sort + 1;
	}
	
}
class Album extends BaseModel { 
	
	const MODE_COUNT			= 1;
	const MODE_FETCH			= 2;
	const FILTER_ALL			= 1;
	const FILTER_PUBLISHED		= 2;
	const FILTER_UNPUBLISHED	= 3;
	const SORT_NORMAL 			= 1;
	const SORT_NEWEST 			= 2;
	const SORT_OLDEST 			= 3;
	
	public $id					= null;
	public $title				= null;
	public $slug				= null;
	public $sort				= null;
	public $published			= null;
	public $date_added			= null;
	public $date_updated		= null;
	
	public function __construct($id = null) {
		parent::__construct("gallery");
		if($id != null) $this->fetch($id);
	}
	
	public function setTitle($str) { $this->title = $str; }
	public function setSlug($str) { $this->slug = $str; }
	public function setSort($int) { $this->sort = $int; }
	public function setPublished($bool) { $this->published = Tools::ReturnBinaryBool($bool); }
	
	public function getID() { return $this->id; }
	public function getTitle() { return $this->title; }
	public function getSlug() { return $this->slug; }
	public function getSort() { return $this->sort; }
	public function isPublished() { return (($this->published == 1)?true:false); }
	public function getDateAdded($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_added); }
	public function getDateUpdated($format = Tools::DATEFORMAT_RAW) { return Tools::FormatDate($format, $this->date_updated); }

	public function getTopImage() {
		$images = $this->getImages(self::MODE_FETCH, self::FILTER_PUBLISHED, self::SORT_NORMAL, 1);
		if(count($images)>0) return $images[0];
		else return new GalleryPhoto(); 
	}
	
	public function save() {
		$nextHighestSort = Album::getNextHighestSort();
		$fields = array( 'title', 'slug', 'published' );
		foreach($fields as $field) $this->db->set($field, $this->{$field});
		$this->db->set('date_updated', 'NOW()', false);
		if($this->getID() == null) $this->db->insert("gallery")->set("sort", $nextHighestSort)->set("date_added", 'NOW()', false);
		else $this->db->update("gallery")->set("sort", $this->getSort())->where("id", $this->getID());
		
		return $this->db->exec();
	}
	
	public function delete() {
		return $this->db->delete("gallery")->where("id", $this->getID())->exec();
	}
	
	public function getImages($mode, $filter = self::FILTER_ALL, $sort = self::SORT_NORMAL, $limit = null) {
		switch($filter) {
			case self::FILTER_ALL:
				break;
			case self::FILTER_PUBLISHED:
				$this->db->where("published", 1);
				break;
			case self::FILTER_UNPUBLISHED:
				$this->db->where("published", 0);
				break;
		}
		switch($sort) {
			case self::SORT_NORMAL:
				$this->db->orderby("sort");
				break;
			case self::SORT_NEWEST:
				$this->db->orderby("date_added", "DESC");
				break;
			case self::SORT_OLDEST:
				$this->db->orderby("date_added");
				break;
		}
		
		if($limit != null) {
			$this->db->limit($limit);
		}
		
		$this->db->select("id")->from("gallery_photos")->where("album", $this->getID())->get();
		
		switch($mode) {
			case self::MODE_COUNT:
				return $this->db->numrows();
				break;
			case self::MODE_FETCH:
				$fetch = $this->db->results();
				$images = array();
				if($this->db->numrows() > 0) foreach($fetch as $image) $images[] = new GalleryPhoto($image->id);
				return $images;
				break;
		}
	}
	
	public static function getAlbums($mode, $filter = self::FILTER_ALL, $sort = self::SORT_NORMAL) {
		global $db;
		switch($filter) {
			case self::FILTER_ALL:
				break;
			case self::FILTER_PUBLISHED:
				$db->where("published", 1);
				break;
			case self::FILTER_UNPUBLISHED:
				$db->where("published", 0);
				break;
		}
		switch($sort) {
			case self::SORT_NORMAL:
				$db->orderby("sort");
				break;
			case self::SORT_NEWEST:
				$db->orderby("date_added", "DESC");
				break;
			case self::SORT_OLDEST:
				$db->orderby("date_added");
				break;
		}
		$db->select("id")->from("gallery");
		switch($mode) {
			case self::MODE_COUNT:
				return $db->get()->numrows();
				break;
			case self::MODE_FETCH:
				$fetch = $db->get()->results();
				$galleries = array();
				if($db->numrows() > 0) foreach($fetch as $gallery) $galleries[] = new Album($gallery->id);
				return $galleries;
				break;
		}
	}
	
	
	public static function getNextHighestSort() {
		global $db;
		$sort = $db->select("sort")->from("gallery")->orderby("sort", "desc")->limit(1)->get()->result();
		return $sort->sort + 1;
	}
	
}