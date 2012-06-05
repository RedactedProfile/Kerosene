<?php
class SocialMedia {
	
	const SIZE_SMALL = 1;
	const SIZE_MED 	 = 2;
	const SIZE_BIG 	 = 3;
	
	public $img = "/images/interface/blank.png";
	public $link = "http://www.something.com/[ACCOUNT]";
	
	public function getAccount() {
		if(class_exists("Settings")) {
			
			return Settings::GetSetting("SM_".strtoupper(get_class($this))); 
			
		} else return false;
	}
	
	public function getLink() {
		
		return str_replace(
			array("[ACCOUNT]"),
			array( $this->getAccount() ),
			$this->link
		);
		
	}
	
	public function setImg($str) { $this->img = $str; }
	public function getImg() { return $this->img; }
	public function getIcon($size = self::SIZE_SMALL) {
		$txt = "<img class='social-media icon-".strtolower(get_class($this))." ' src='".$this->getImg()."' ";
		switch($size) {
			case self::SIZE_SMALL:
				$txt .= "width='50' height='50'";
				break;
			case self::SIZE_MED:
				$txt .= "width='100' height='100'";
				break;
			case self::SIZE_BIG:
				$txt .= "width='150' height='150'";
				break;
		}
		
		return $txt . " />";
	}
	
	public function __toString() {
		echo $this->getIcon();
	}
	
	public static function getSocialMedia() {
		global $db;
		$media = array();
		$fetch = $db->select(array("key", "value"))->from("settings")->wherelike("key", "SM_", "right")->where("value", "", "<>")->get()->results();
		if($db->numrows() > 0) {
			foreach($fetch as $m) {
				$class = "SocialMedia";
				switch($m->key) {
					default:
						$br = explode("_", $m->key);
						$class = ucfirst($br[1]);
						break;
					case "SM_GOOGLEPLUS":
						$class = "GooglePlus";
						break;
					case "SM_LINKEDIN":
						$class = "LinkedIn";
						break;
					case "SM_YOUTUBE":
						$class = "YouTube";
						break;
						
				}
				
				if(class_exists($class)) {
					$media[] = new $class;
				} else $media[] = new SocialMedia;
			}
		}
		
		return $media;
	}
}

class Facebook extends SocialMedia {
	public $img = "/images/interface/facebook.png";
	public $link = "http://www.facebook.com/[ACCOUNT]";
}

class Twitter extends SocialMedia {
	public $img = "/images/interface/twitter.png";
	public $link = "http://www.twitter.com/#!/[ACCOUNT]";
}

class GooglePlus extends SocialMedia {
	public $img = "/images/interface/google+.png";
	public $link = "http://plus.google.com/[ACCOUNT]";
}

class YouTube extends SocialMedia {
	public $img = "/images/interface/youtube.png";
	public $link = "http://www.youtube.com/[ACCOUNT]";
}

class Flickr extends SocialMedia {
	public $img = "/images/interface/flickr.png";
	public $link = "http://www.flickr.com/[ACCOUNT]";
}

class Tumblr extends SocialMedia {
	public $img = "/images/interface/tumblr.png";
	public $link = "http://[ACCOUNT].tumblr.com";
}

class LinkedIn extends SocialMedia {
	public $img = "/images/interface/linkedin.png";
	public $link = "http://www.linkedin.com/in/[ACCOUNT]";
}