<?php
class Gravatar {
	
	private $email		= null;
	private $default	= null;
	private $size		= null;
	private $rating		= null;
	
	private $hash		= null;
	
	public $image		= null;
	
	private $ratings	= array(
		"g", "pg", "r", "x"
	);
	
	/**
	 * <b>Gravitar Interface 1.0</b><br />
	 * Can take an array of settings, or use the optional parameters to get up and running right away, or use like a normal class and call each function individually
	 */
	public function __construct($email = null, $size = null, $default = null, $rating = null) {
		if(is_array($email)) {
			$settings = $email;
			
			foreach($settings as $setting=>$value) {
				switch($setting) {
					case "email":
						$this->email($value);
						break;
					case "size":
						if(is_int($value)) $this->size($value);
						else {
							if(method_exists($this, $value)) call_user_method($value, $this);
						}
						break;
					case "default":
						$this->default_image($value);
						break;
					case "rating":
						$this->rating($value);
						break;
				}
			}
		} else {
			
			(($this->NotNull($email)) ? $this->email($email) : null );
			(($this->NotNull($size)) ? ((is_int($size)) ? $this->size($size) : ((method_exists($this, $size)) ? call_user_method($size, $this) : null ) ) : null ); // does size exist? use it. Is it an int? use that to set, else is it a string? try to call the function of the same name, otherwise just dont do anything
			(($this->NotNull($default)) ? $this->default_image($default) : null );
			(($this->NotNull($rating)) ? $this->rating($rating) : null );
			
		}
		
		
		$this->get();
	}
	
	public function email($email = null) {
		if(!$this->NotNull($email) || !preg_match("/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/", $email) )
			return false;
		
		$this->email = $email;
		
		return true;
	}
	
	/**
	 * This is the default image to use if a gravatar is not found
	 */
	public function default_image($img = null) {
		// URL VALIDATION
		// FILE EXISTS VALIDATION
		$this->default = $img;
		
		return true;
	}
	
	public function rating($rating = null) {
		if(!$this->NotNull($rating) || !in_array($rating, $this->ratings)) return false;
		$this->rating = array_search($rating, $this->ratings);
	}
	
	/**
	 * Forced image size, can accept only integer values between 1 - 512
	 * @param int $size an integer value between 1px and 512px 
	 */
	public function size($size = null) {
		if(!$this->NotNull($size) || !is_int($size) || ($size < 1 || $size > 512)) return false;
		
		$this->size = $size;
		return $this->size;
	}
	
	public function huge() {
		return $this->size(512);
	}
	
	public function big() {
		return $this->size(256);
	}
	
	public function medium() {
		return $this->size(128);	
	}
	
	public function small() {
		return $this->size(64);
	}
	
	public function tiny() {
		return $this->size(32);
	}
	
	public function xtiny() {
		return $this->size(16);
	}
	
	// Checks if a provided value is null or empty
	private function NotNull($val) {
		if($val == null || trim($val) == "") return false;
		else return true;
	}
	
	
	
	public function get() {
		if($this->NotNull($this->email)) { // Only Email is required
			$this->hash = md5(strtolower($this->email));
			$url = 'http://www.gravatar.com/avatar/'. $this->hash;
			$params = array();
			(($this->NotNull($this->rating)) ? $params[] = "r=".$this->ratings[$this->rating] : $params[] = "r=".$this->ratings[0] );
			(($this->NotNull($this->size)) ? $params[] = "s=".$this->size : $params[] = "s=".$this->small() );
			(($this->NotNull($this->default)) ? $params[] = "d=".$this->default : $params[] = "d=mm" );
			
			if(count($params)>0) $url .= "?".implode("&", $params);
			
			$this->image = $url;
			
			return $this->image;
		} else return false;
	}
	
}
