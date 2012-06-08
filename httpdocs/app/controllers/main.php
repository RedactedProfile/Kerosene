<?php
/*
	
	"A person with a new idea is a crank until the idea succeeds." ~Mark Twain
	
	"There are three methods to gaining wisdom. 
	The first is reflection, which is the highest. 
	The second is limitation, which is the easiest. 
	The third is experience, which is the bitterest." ~Confucius
	
	~K
*/
class main extends BaseController {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		
		if($this->uri->method) {
			$this->uri->method = str_replace("_", "-", $this->uri->method);
			$this->data['cms'] = new CMS($this->uri->method);
			$this->data['root'] = $this->uri->controller;
			load::view("header", $this->data);
			load::view("cms.basic", $this->data);
			load::view("footer");
		} else {
			$this->data['cms'] = CMS::GetDomainHomepage($this->data['domain']);
			$this->data['root'] = "main";
			load::view("header", $this->data);
			load::view("index", $this->data);
			load::view("footer");
		}

		
		
	}

	
}
