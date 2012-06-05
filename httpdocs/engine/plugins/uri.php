<?php
class uri {
	
	public $uri 		= null;
	public $segments 	= array();
	public $controller 	= null;
	public $method 		= null;
	public $arguments 	= array();
	
	function __construct() {
		$this->uri = $_REQUEST['uri'];
		$this->break_segments();
	}
	
	public function segment($index) {
		return $this->segments[$index];
	}
	
	public function attr($index) {
		return $this->segments[$index];
	}
	
	public function isempty() {
		if(empty($this->segments) || count($this->segments) <= 0) return true;
		else return false;
	}
	
	public function attributes() {
		return implode(", ", $this->arguments);
	}
	
	private function break_segments() {
		$segments = explode("/", $this->uri);
		
		
		if(count($segments)>0 && trim($segments[0]) != '') {
			
			$this->segments = $segments;
			
			if(!empty($segments)) {
				$this->controller = array_shift($segments); 
			}
			if(!empty($segments)) {
				$this->method = array_shift($segments);
			}
			if(!empty($segments)) {
				$this->arguments = $segments;
			}
		}
		
		
		return true;
	}
}