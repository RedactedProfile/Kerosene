<?
class BaseController {

	public $db;
	public $uri;
	
	public $data;
	
	public function __construct() {
		global $db, $uri;
		$this->db = $db;
		$this->uri = $uri;
		
		if(!Session::data("currentDomain")) Session::data("currentDomain", Domain::SetCurrentDomain());
		$this->data['domain'] = Session::data("currentDomain");
		
		$this->data['pages'] = CMS::getPagesFilter(0, Session::data("currentDomain"), CMS::MODE_FETCH, null, CMS::DISPLAY_PUBLISHED, "sort", "asc");
	}

}