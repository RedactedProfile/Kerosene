<?
class BlogCategory extends BaseModel {
	public $id				= null;
	public $title			= null;
	public $slug			= null;
	public $date_created	= null;
	
	public function __construct($id = null) {
		parent::__construct("blog_categories");
		if($id != null) $this->fetch($id);
	}
	
	public function setTitle($str) { $this->title = $str; }
	public function setSlug($str) { $this->slug = $str; }
	public function getID() { return $this->id; }
	public function getTitle() { return $this->title; }
	public function getSlug() { return $this->slug; }
	public function getDateCreated($formatted = true) { return $this->date_created; }
	
	public function save() {
		$this->db->set("title", $this->getTitle())->set("slug", $this->getSlug());
		if($this->getID() == null) $this->db->insert("blog_categories")->set("date_created", "NOW()", false);
		else $this->db->update("blog_categories")->where("id", $this->getID());
		return $this->db->exec();
	}
	
	public function delete($newCategory = 1) { 
		// we will be doing two things here, moving ALL existing posts under this category to the specified category (1 = Uncategoried)
		// and THEN deleting the category from the database
		if($this->db->update("blog")->set("category", $newCategory)->where("category", $this->getID())->exec()) {
			return $this->db->delete("blog_categories")->where("id", $this->getID())->exec();
		}
		
		return false;
	}
	
	public static function getCategories() {
		global $db;
		$cats = array();
		$fetch = $db->select("id")->from("blog_categories")->orderby("title")->get()->results();
		if($db->numrows() > 0) foreach($fetch as $c) $cats[] = new BlogCategory($c->id);
		return $cats;
	}
}

class Blog extends BaseModel {
	
	// Consts
	
	/**
	* Will filter posts that are published and past the published date. useful for front end.
	*/
	const FILTER_PUBLISHED_ONLY 	= 1;
	/**
	* Will filter posts to be published only, regardless of date.
	*/
	const FILTER_PUBLISHED_ALL 		= 2;
	/**
	* Will filter posts that are unpublished and past the published date.
	*/
	const FILTER_UNPUBLISHED_ONLY 	= 3;
	/**
	* Will filter posts to be published only, regardless of date.
	*/
	const FILTER_UNPUBLISHED_ALL 	= 4;
	/**
	* Will not filter posts at all.
	*/
	const FILTER_ALL 				= 5;
	/**
	* Returns actual results
	*/
	const MODE_FETCH				= 1;
	/**
	* Returns Record Count
	*/
	const MODE_COUNT				= 2;
	/**
	 * Returns a queried result
	 */
	const MODE_SEARCH				= 3;
	/**
	 * Sorts the list by newest to oldest
	 */
	const SORT_NEWEST				= 1;
	/**
	 * Sorts the list by oldest to newest
	 */
	const SORT_OLDEST				= 2;
	
	
	
	
	public $id				= null;
	public $category		= null;
	public $slug			= null;
	public $title			= null;
	public $content			= null;
	public $featured_image	= null;
	public $published		= null;
	public $meta_title		= null;
	public $meta_keys		= null;
	public $meta_description= null;
	public $date_published	= null;
	public $date_created	= null;
	public $date_updated	= null;

	public function __construct($ref = null) {
		parent::__construct("blog");
		if($ref != null) $this->fetch($ref);
	}
	
	public function setCategory($int) { $this->category = $int; }
	public function setSlug($str) { $this->slug = $str; }
	public function setTitle($str) { $this->title = $str; }
	public function setContent($str) { $this->content = $str; }
	public function setFeaturedImage($str) { $this->featured_image = $str; }
	public function setMetaTitle($str) { $this->meta_title = $str; }
	public function setMetaKeys($str) { $this->meta_keys = $str; }
	public function setMetaDescription($str) { $this->meta_description = $str; }
	public function setPublished($bool) { $this->published = Tools::ReturnBinaryBool($bool); }
	public function setDatePublished($date) { $this->date_published = Tools::FormatDate(Tools::DATEFORMAT_MYSQL, $date); } // 2012-05-04 13:45:05
	
	public function getID() { return $this->id; }
	public function getCategory() { return new BlogCategory($this->getCategoryID()); }
	public function getCategoryID() { return $this->category; }
	public function getSlug() { return $this->slug; }
	public function getTitle() { return $this->title; }
	public function getContent() { return $this->content; }
	public function getFeaturedImage() { return $this->featured_image; }
	public function getFeaturedThumbnail($width = 150, $height = 150) { return "/thumb.php?path=blog|".$this->getFeaturedImage()."&width=".$width."&height=".$height; }
	public function getPublished() { return (($this->published == 1)?true:false); }
	public function getMetaTitle() { return $this->meta_title; }
	public function getMetaKeys() { return $this->meta_keys; }
	public function getMetaDescription() { return $this->meta_description; }
	public function getDatePublished($format = Tools::DATEFORMAT_SHORT) { return Tools::FormatDate($format, $this->date_published); }
	public function getDateCreated($format = Tools::DATEFORMAT_SHORT) { return Tools::FormatDate($format, $this->date_created); }
	public function getDateUpdated($format = Tools::DATEFORMAT_SHORT) { return Tools::FormatDate($format, $this->date_updated); }

	
	public function save() {
		
		$fields = array( 
			'category',
			'slug',
			'title',
			'content',
			'featured_image',
			'published',
			'meta_title',
			'meta_keys',
			'meta_description',
			'date_published'
		);
		
		foreach($fields as $f) $this->db->set($f, $this->{$f});

		if($this->getID() == null) $this->db->insert("blog")->set("date_created", "NOW()", false);
		else $this->db->update("blog")->where("id", $this->getID())->set("date_updated", "NOW()", false);
		
		
		return $this->db->exec();
		
	}
	
	public function delete() {
		return $this->db->delete("blog")->where("id", $this->getID())->exec();
	}
	
	
	
	public static function getPosts($mode, $sort = Blog::SORT_NEWEST, $page = null, $limit = null, $published = null, $category = null, $query = null) {
		global $db;
		
		$posts = array();
		
		if($published != null) {
			
			switch($published) {
				
				case Blog::FILTER_PUBLISHED_ONLY:
					$db ->where("published", 1)
						->where("date_published", "NOW()", "<=", false);
					break;
				case Blog::FILTER_PUBLISHED_ALL:
					$db->where("published", 1);
					break;
				case Blog::FILTER_UNPUBLISHED_ONLY:
					$db->where("published", 0)
					   ->where("date_published", "NOW()", "<=", false);
					break;
				case Blog::FILTER_UNPUBLISHED_ALL:
					$db->where("published", 0);
					break;
				case Blog::FILTER_ALL:
				default:
					// lol do nothing
					break;
				
			}
			
		}
		
		if($category != null) {
			$db->where("category", $category);
		}
		
		switch($sort) {
			case self::SORT_NEWEST:
				$db->orderby("date_published", "DESC");
				break;
			case self::SORT_OLDEST:
				$db->orderby("date_published", "ASC");
				break;
		}
		
		$db->select("id")->from( "blog" );
		
		switch($mode) {
			case Blog::MODE_COUNT:
				$db->exec();
				
				return $db->numRows();
				
				break;
			case Blog::MODE_FETCH:
				
				$fetch = $db->limit( (($page != null)? ($page-1)*$limit : "" )  .  (($limit != null)?", $limit":"")  ) 
					// kinda messy: if $page is not null, detract 1 from page number (multiplication purposes) and checks if $limit is null, if not the use it
					->get()->results();
			
				if($db->numRows()>0) foreach($fetch as $post) $posts[] = new Blog($post->id);
				
				return $posts;
				
				break;
			case Blog::MODE_SEARCH:
				
				$db->wherelike("title", $query, "right")->orwherelike("title", $query)->orwherelike("content", $query)
				->orderby_override("case when `title` LIKE '$query%' then 1 else 0 end + case when `title` LIKE '%$query%' then 1 else 0 end + case when `content` LIKE '%$query%' then 1 else 0 end DESC");
				
				$fetch = $db->get()->results();
				/*
				var_dump($query);
				
				$union1 = clone $db;
				$union1->wherelike("title", $query, "right");
				$union2 = clone $db;
				$union2->wherelike("title", $query);
				$union3 = clone $db;
				$union3->wherelike("content", $query);
				
				//$db->join($table, $alias, $on_original, $on_compare)
				
				//$fetch = $db->get()->results();
				
				Debug::dump($union1);
				*/
				if($db->numrows() > 0) foreach($fetch as $post) $posts[] = new Blog($post->id);
				
				return $posts;
				
				break;
		}
		
	}
	
	
	public static function Truncate($text, $limit = 40, $padding = "...") {
		$text = explode(" ", $text);
		$ret = array();
		foreach($text as $num=>$t) {
			if($num <= $limit) $ret[] = $t;
			else break;
		}
		
		return strip_tags(join(" ", $ret), '<b><em><strong><i><br><br />').$padding;
	}
}