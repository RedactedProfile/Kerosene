<?php
class RSS {
	
	const RSS_BLOG = 1;
	const RSS_LOTS = 2;
	
	public function __construct($type) {
		$this->GenerateRSS($type);
	}
	
	public function GenerateRSS($type) {
		switch($type) {
			
			case self::RSS_BLOG:
				break;
			
			
		}
	}
	
	
}