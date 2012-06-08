<?php
/*
	
	"A person with a new idea is a crank until the idea succeeds." ~Mark Twain
	
	"There are three methods to gaining wisdom. 
	The first is reflection, which is the highest. 
	The second is limitation, which is the easiest. 
	The third is experience, which is the bitterest." ~Confucius
	
	~K
*/
class cmsBasic extends BaseController {

	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		
		$this->uri->controller = str_replace("_", "-", $this->uri->controller);
		$this->data['cms'] = new CMS($this->uri->controller);
		$this->data['root'] = $this->uri->controller;
		load::view("header", $this->data);
		load::view("cms.basic", $this->data);
		load::view("footer");
		
	}


	public function __call($method, $args) {

		//die(var_dump($this->uri));
		$this->uri->method = str_replace("_", "-", $this->uri->method);
		$this->data['cms'] = new CMS($this->uri->method);
		$this->data['root'] = $this->uri->controller;
		load::view("header", $this->data);
		load::view("cms.basic", $this->data);
		load::view("footer");

	}
	
}

