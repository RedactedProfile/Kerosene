<?php
class admin extends BaseAdmin {
	public function __construct() {
		parent::__construct();
	
		$this->data['title'][] = $this->data['header']['pages'][(($this->uri->method != null)?$this->uri->method:'')];
	
		load::model("Account");
		if(Account::GetData() == null && ( $this->uri->method != "secure" && $this->uri->method != "login" )) {
			redirect("/admin/secure");
			die();
		}
	
	
		$this->data['avatar'] = new Gravatar(Account::GetData("gravatar"));
	
	}
	
	
	public function pages() {
		if(!isset($this->uri->arguments[0])) {
			redirect("/admin/pages/list");
			die();
		}
	
		$slugToEdit = explode("/", $this->uri->uri);
		$slugToEdit = array_pop($slugToEdit);
	
	
	
		switch($this->uri->arguments[0]) {
				
			case "list":
					
	
				if(!Session::data("currentDomain")) Domain::SetCurrentDomain();
					
				$this->data['domains'] = Domain::GetDomains();
				$this->data['pages'] = CMS::getPagesFilter(0, Session::data("currentDomain"), CMS::MODE_FETCH, null, CMS::DISPLAY_ALL, "sort");
					
				load::view("admin/header", $this->data);
				load::view("admin/list.page", $this->data);
				load::view("admin/footer");
				break;
					
			case "save":
	
	
				if(isset($this->uri->arguments[1]) && $this->uri->arguments[1] == "new") {
					$page = new CMS();
					$page->setCategory( ((isset($this->uri->arguments[2]))?$this->uri->arguments[2]:0) );
						
				} else {
					$page = new CMS($slugToEdit);
				}
	
				$page->setTitle( post("title") );
				if($this->uri->arguments[1] == "new") $page->setSlug( Tools::Slug($page->getTitle()) ); // do NOT overwrite the slug field of any of the main categories (all category '0')
				$page->setContent($_POST["content"] );
				$page->setDomain(Session::data("currentDomain"));
				$page->setMetaTitle( post("meta_title") );
				$page->setMetaKeys( post("meta_keys") );
				$page->setMetaDescription( post("meta_description") );
				$page->setDisplay( Tools::ReturnBinaryBool( post("display") ) );
	
	
				if(trim($page->getTitle())=="") $page->setTitle("New Page");
	
	
				if($page->save()) Session::flash("sysmsg", (string)new Success("Page <em>'".$page->getTitle()."'</em> saved successfully"));
				else Session::flash("sysmsg", (string)new Error("Page <em>'".$page->getTitle()."'</em> could not be saved at this time"));
	
				//redirect("/admin/pages/edit/".$slugToEdit);
				redirect("/admin/pages/list");
	
	
				break;
	
			case "new-sub":
				$this->data['cms'] = new CMS();
				$this->data['root_page'] = $this->data['cms']->getSlug();
				$this->data['parent'] = $this->uri->arguments[1];
	
				load::view("admin/header", $this->data);
				load::view("admin/new.page", $this->data);
				load::view("admin/footer");
				break;
			case "edit":
	
				$this->data['cms'] = new CMS($slugToEdit);
	
				$this->data['root_page'] = null;
				if($this->data['cms']->getCategory() != 0) { // this has a parent category
					$this->data['root_page'] = $this->data['cms']->getParent()->getSlug();
				} else {
					$this->data['root_page'] = $this->data['cms']->getSlug();
				}
	
				$this->data['slugs'] = implode("/", $this->uri->arguments);
	
				load::view("admin/header", $this->data);
				load::view("admin/edit.page", $this->data);
				load::view("admin/footer");
				break;
			case "new":
					
				$this->data['cms'] = new CMS();
				$this->data['root_page'] = $this->data['cms']->getSlug();
				$this->data['slugs'] = implode("/", $this->uri->arguments);
	
				load::view("admin/header", $this->data);
				load::view("admin/new.page", $this->data);
				load::view("admin/footer");
	
				break;
			case "delete":
	
				//if($this->uri->arguments[1] == $slugToEdit) redirect("/admin/pages/$slugToEdit"); // safety net in case someone tries to delete a root page
	
				$page = new CMS($slugToEdit); // in this case, a slug should be an ID number, the fetch method will figure this out for us
	
				if($page->delete()) Session::flash("sysmsg", (string)new Success("Page <em>'".$page->getTitle()."'</em> deleted successfully"));
				else Session::flash("sysmsg", (string)new Error("Page could not be removed at this time, please try again"));
	
				//redirect("/admin/pages/".$this->uri->arguments[1]);
				redirect("/admin/pages");
	
				break;
			case "make-homepage":
	
				if(!$this->uri->arguments[1]) redirect("/admin/pages/list");
				if(!Session::data("currentDomain")) Domain::SetCurrentDomain();
	
				if(CMS::MakeHompage($this->uri->arguments[1], Session::data("currentDomain"))) Session::flash("sysmsg", (string)new Success("New homepage set!"));
				else Session::flash("sysmsg", (string)new Error("Could not set homepage at this time"));
	
				redirect("/admin/pages/list");
	
				break;
		}
	
	
	
	}
}