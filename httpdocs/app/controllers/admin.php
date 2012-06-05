<?
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
	
	public function index() {
		load::view("admin/header", $this->data);
		load::model("Blog");
		$this->data['count'] = (object)array(
			"TotalPages"=>CMS::getPagesFilter(null, CMS::MODE_COUNT),
			"IncompletePages"=>CMS::getPagesFilter(null, CMS::MODE_COUNT, CMS::META_ALL),
			"BlogPosts"=>Blog::getPosts(Blog::MODE_COUNT, null, null, BLOG::FILTER_ALL)
		); 
		
		load::view("admin/index", $this->data);
		load::view("admin/footer");
	}
	
	public function secure() {
		if(Account::GetData() != null) redirect("/admin");
		
		load::view("admin/header.lite", $this->data);
		load::view("admin/login");
		load::view("admin/footer.lite");
	}
	
	public function login() {
		if(post()) {
			
			$acc = new Account();
			$acc->setLogin(post("login"));
			$acc->setPassword(post("password"));
			
			if($acc->login()) {
				redirect("/admin");
			} else {
				Session::flash("login_error", (string) new Error("Sorry, credentials were not found in the system"));
				redirect("/admin/secure");
			}
			
			
		} else redirect("/admin");
	}
	
	public function logout() {
		Account::kill();
		redirect("/admin");
	}
	
	
	/**********************/
	
	/**********************/
	
	public function domains() {
		load::model("Domain");
		if(!isset($this->uri->arguments[0])) {
			redirect("/admin/domains/list");
			die();
		}
		
		switch($this->uri->arguments[0]) {
			case "list":
				$this->data['domains'] = Domain::GetDomains();
				load::view("admin/header", $this->data);
				load::view("admin/list.domains", $this->data);
				load::view("admin/footer");
				break;
			case "new":
				load::view("admin/header", $this->data);
				load::view("admin/new.domains", $this->data);
				load::view("admin/footer");
				break;
			case "save":
				
				$domain = new Domain();
				$domain->setActive(Tools::ReturnBinaryBool(post("activated")));
				$parsed = explode(".", preg_replace(
						array(
							"/https?\:\/\//i",
							"/www\./"
						),
						"",
						post("domain")
					)
				);
				$domain->setName($parsed[0]);
				$domain->setDomain($parsed[1]);
				
				if($domain->save()) Session::flash("sysmsg", (string)new Success("Domain has been added successfully"));
				else Session::flash("sysmsg", (string)new Error("Domain could not be saved at this time"));
				
				redirect("/admin/domains/list");
				
				break;
			case "delete":
				
				$domain = new Domain($this->uri->arguments[1]);
				if(!$domain->getID()) redirect("/admin/domains/list");

				if($domain->delete()) Session::flash("sysmsg", (string)new Success("Domain has been removed successfully"));
				else Session::flash("sysmsg", (string)new Error("Domain could not be removed at this time"));
				
				redirect("/admin/domains/list");
				
				break;
		
		}
		
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
	
	
	/*
	 * This gallery one is probably the most confusing in the system. In this function is two tiers of depth, and the URLs need to reflect this
	 *  Gallery List
	 *     |-Edit Gallery
	 *     |-Photo List
	 *     		|- Edit Photo
	 *  
	 *  This particular funciton makes a few thigns confusing, but im trying to keep it as clean as possible. Apologies in advance if this
	 *  causes anyone any internal melodrama.
	 */
	
	public function gallery() {
		ini_set("post_max_size", "50M");
		ini_set("upload_max_filesize", "50M");
		load::model("Gallery");
		
		if(!isset($this->uri->arguments[0])) {
			redirect("/admin/gallery/list");
			die();
		}
		
		switch($this->uri->arguments[0]) {
			default:
			case "list":
				load::view("admin/header", $this->data);
				$this->data['count'] = Album::getAlbums(Album::MODE_COUNT);
				$this->data['galleries'] = Album::getAlbums(Album::MODE_FETCH);
				load::view("admin/list.gallery", $this->data);
				load::view("admin/footer");
				break;
			case "save-sort":
				$json = array("status"=>"false", "msg"=>"");
					
				$gallery = null;
				foreach($_POST['order'] as $sort=>$gallery) {
					$g = new Album($gallery);
					$g->setSort($sort);
					if(!$g->save()) {
						$json['msg'] = "could not save $gallery";
					}
					
				}
				
				if($json['msg'] == "")
					$json['status'] = "true";
				
				echo json_encode($json);
				
				break;
			case "save":
				
				if(isset($this->uri->arguments[1]) && $this->uri->arguments[1] != "photo") $g = new Album($this->uri->arguments[1]);
				else $g = new Album();
				
				$g->setTitle(post("title"));
				if(!$g->getSlug()) $g->setSlug(Tools::Slug(post("title")));
				$g->setPublished(post("published"));
				
				if($g->save()) Session::flash("sysmsg", (string)new Success("Gallery <em>".$g->getTitle()."</em> saved successfully"));
				else Session::flash("sysmsg", (string)new Error("Gallery <em>".$g->getTitle()." could not be saved at this time, please try again</em>"));
				
				redirect("/admin/gallery");
				
				break;
			case "edit":
				
				if(!isset($this->uri->arguments[1])) redirect("/admin/gallery");
				
				if(!isset($this->uri->arguments[2])) $this->uri->arguments[2] = 'list';
				
				switch($this->uri->arguments[2]) {
					default:
					case "list":
						
						load::view("admin/header", $this->data);
						$this->data['gallery'] = new Album($this->uri->arguments[1]);
						$this->data['photos'] = $this->data['gallery']->getImages(Album::MODE_FETCH);
						load::view("admin/list.gallery.photos", $this->data);
						load::view("admin/footer");
						
						break;
					case "photo":
						
						if(!isset($this->uri->arguments[3])) redirect("/admin/gallery");
						
						switch($this->uri->arguments[3]) {
							case "save":
								
								$gallery = new Album(post("gallery"));
								$file = new Upload( Upload::getFiles('photo') );
								if($file->isImage()) {
									$file->forcePath();
									$file->setPath("/images/gallery/".$gallery->getSlug()."/");
									$file->setName( sha1(strtotime("now"))."_".$file->getName() );
									$file->resize(Settings::GetSetting("GALLERY_MAX_IMAGE_WIDTH", 1024), Settings::GetSetting("GALLERY_MAX_IMAGE_HEIGHT", 800), 100, true);
									if($file->exec()) {
										
										$p = new GalleryPhoto();
										$p->setAlbum( $gallery->getID() );
										$p->setImage($file->getFileName());
										$p->setCaption(post("caption"));
										$p->setPublished(post("published"));
										if($p->save()) Session::flash("sysmsg", (string)new Success("File uploaded successfully"));
										else Session::flash("sysmsg", (string)new Error("File coult not be added to the database at this time"));
									} else {
										Session::flash("sysmsg", (string)new Error("File coult not be uploaded at this time, check the path"));
									}
								} else {
									Session::flash("sysmg", (string)new Error("File was not of an applicable image"));
								}
								
								redirect("/admin/gallery/edit/".$gallery->getSlug());
								
								die();
								
								break;
							case "batch":
								$errors = array();
								$gallery = new Album(post("gallery"));
								$width = Settings::GetSetting("GALLERY_MAX_IMAGE_WIDTH", 1024);
								$height = Settings::GetSetting("GALLERY_MAX_IMAGE_HEIGHT", 800);
								foreach(Upload::getBatchFiles('batch') as $file) {
									
									if($file->isImage()) {
										$file->forcePath();
										$file->setPath("/images/gallery/".$gallery->getSlug()."/");
										$file->setName( sha1(strtotime("now"))."_".$file->getName() );
										$file->resize($width, $height, 80, true);
										if( $file->exec() ) {
											
											$p = new GalleryPhoto();
											$p->setAlbum( $gallery->getID() );
											$p->setImage($file->getFileName());
											$p->setPublished( post("published") );
											if(!$p->save()) {
												$errors[] = "Image: ".$p->getImage()." could not be saved to the database";
											}
											
										} else {
											$errors[] = "Image: ".$file->getFileName()." could not be saved";
										}
										
									}
									
								}
								
								if(count($errors)>0) Session::flash("sysmsg", (string)new Error("Not everything went according to plan: <ul><li>".explode("</li><li>", $errors)."</li></ul>")); 
								else Session::flash("sysmsg", (string)new Success("All images have been added successfully"));
								
								
								redirect("/admin/gallery/edit/".$gallery->getSlug());
								
								
								die();
								
								break;
							case "update":
								if(!isset($this->uri->arguments[4])) redirect("/admin/gallery");
								$gallery = new Album($this->uri->arguments[1]);
								
								$photo = new GalleryPhoto($this->uri->arguments[4]);
								$photo->setCaption(post("caption"));
								$photo->setPublished(post("published"));
								if($photo->save()) Session::flash("sysmsg", (string)new Success("Photo saved successfully"));
								else Session::flash("sysmsg", (string)new Error("Photo could not be saved at this time"));
								
								redirect("/admin/gallery/edit/".$gallery->getSlug());
								
								die();
								
								break;
							case "save-sort":
								
								$json = array("status"=>"false", "msg"=>"");
									
								$photo = null;
								foreach($_POST['order'] as $sort=>$photo) {
									$p = new GalleryPhoto($photo);
									$p->setSort($sort);
									if(!$p->save()) {
										$json['msg'] = "could not save $photo";
									}
								
								}
								
								if($json['msg'] == "")
									$json['status'] = "true";
								
								echo json_encode($json);
								die();
								break;
							case "delete":
								
								if(!isset($this->uri->arguments[4])) redirect("/admin/gallery");
								$gallery = new Album($this->uri->arguments[1]);
								
								$photo = new GalleryPhoto($this->uri->arguments[4]);
								
								$path = $_SERVER['DOCUMENT_ROOT']."/images/gallery/".$gallery->getSlug()."/".$photo->getImage();
								if(file_exists($path)) {
									@unlink($path);
								}
								if($photo->delete()) Session::flash("sysmsg", (string)new Success("Photo deleted successfully"));
								else Session::flash("sysmsg", (string)new Error("Photo could not be deleted at this time"));
								
								
								redirect("/admin/gallery/edit/".$gallery->getSlug());
								die();
								
								break;
						}
						
						load::view("admin/header", $this->data);
						
						break;
					
				}
				
				
				
				
				
				break;
			case "delete":
				if(!isset($this->uri->arguments[1])) redirect("/admin/gallery");
				
				$g = new Album($this->uri->arguments[1]);
				
				// cleanup the images and the database
				$images = $g->getImages(Album::MODE_FETCH, Album::FILTER_ALL, Album::SORT_NORMAL);
				foreach($images as $image) {
					$file = $_SERVER['DOCUMENT_ROOT']."/images/gallery/".$g->getSlug()."/".$image->getImage();
					if(file_exists($file)) @unlink($file);
					$image->delete();
				}
				if(is_dir($_SERVER['DOCUMENT_ROOT']."/images/gallery/".$g->getSlug())) {
					rmdir($_SERVER['DOCUMENT_ROOT']."/images/gallery/".$g->getSlug());
				}
				
				// cleanup form the images database
				
				
				if($g->delete()) Session::flash("sysmsg", (string)new Success("Gallery <em>".$g->getTitle()."</em> deleted"));
				else Session::flash("sysmsg", (string)new Error("Gallery <em>".$g->getTitle()."</em> could not be deleted at this time"));
				
				redirect("/admin/gallery");
				
				break;
				
			case "photo":
				die("test");
				if(!isset($this->uri->arguments[1])) redirect("/admin/gallery");
				
				
				
				
				break;
		}
		
		
		
		
	}
	
	
	
	public function blog() {
		load::model("Blog");
		
		if(!isset($this->uri->arguments[0])) {
			redirect("/admin/blog/list/1");
			die();
		}
		
		switch($this->uri->arguments[0]) {
			default:
			case "list":
			case "edit":
			case "new":
				load::view("admin/header", $this->data);
			break;
		}
		
		switch($this->uri->arguments[0]) {
			default:
			case "list":
				if(!isset($this->uri->arguments[1])) $this->uri->arguments[1] = 1; // default to first page if no page data exists

				$this->data['count'] = Blog::getPosts( Blog::MODE_COUNT );
				$paginate = new Paginate( $this->data['count'], Settings::GetSetting("ADMIN_POSTS_PER_PAGE", 5), '/admin/blog/list/[#]' );
				$paginate->setCurrentPage( $this->uri->arguments[1] );

				$this->data['pages'] = $paginate->render(Paginate::RENDER_HTML);
				$this->data['posts'] = Blog::getPosts( Blog::MODE_FETCH, Blog::SORT_NEWEST, $this->uri->arguments[1], Settings::GetSetting("ADMIN_POSTS_PER_PAGE", 5) );
				
				load::view("admin/list.blog", $this->data);
				
				break;
			case "search":
				
				if(empty($_POST)) redirect("/admin/blog");
				
				$this->data['count'] = "";
				$this->data['pages'] = "";
				$this->data['posts'] = Blog::getPosts( Blog::MODE_SEARCH, null, null, null, Blog::FILTER_ALL, null, post('q') );
				Session::flash("sysmsg", (string)new Message("Results are ordered by relevance. <a href='/admin/blog'>Remove Filter</a>"));
				load::view("admin/list.blog", $this->data);
				
				break;
			case "edit":
				
				if(!isset($this->uri->arguments[1])) redirect("/admin/blog");
				$this->data['post'] = new Blog($this->uri->arguments[1]);
				$this->data['categories'] = BlogCategory::getCategories();
				load::view("admin/edit.blog", $this->data);
				
				break;
			case "new":
				
				$this->data['post'] = new Blog();
				$this->data['categories'] = BlogCategory::getCategories();
				load::view("admin/edit.blog", $this->data);
								
				break;
			case "delete":
				
				if(!isset($this->uri->arguments[1])) redirect("/admin/blog");
				$blog = new Blog($this->uri->arguments[1]);
				if($blog->delete()) Session::flash("sysmsg", "Blog entry deleted successfully");
				else Session::flash("sysmsg", "Blog entry could not be removed at this time, please try again");
				
				redirect("/admin/blog");
				
				break;
			case "save":
				if(empty($_POST)) redirect("/admin/blog");
				
				if(isset($this->uri->arguments[1])) $blog = new Blog($this->uri->arguments[1]);
				else $blog = new Blog();

				$blog->setTitle( post("title") );
				if($blog->getSlug() == null) $blog->setSlug( Tools::Slug( $blog->getTitle() ) );
				
				if(trim(post("new_category")) != "") { // create new category
					
					$category = new BlogCategory();
					$category->setTitle( post("new_category") );
					$category->setSlug( Tools::Slug(post("new_category")) );
					$category->save();
					
					$blog->setCategory( $this->db->insertID() );
					
				} else {
					$blog->setCategory( post("category") );
				}
				$blog->setContent( $_POST["content"] );

				$blog->setMetaDescription( post("meta_description") );
				$blog->setMetakeys( post("meta_keys") );
				$blog->setMetaTitle( post("meta_title") );
				
				$blog->setPublished( ((isset($_POST['post_visible']))?1:0) );
				
				$blog->setDatePublished( post("edit_publish_time_day")."-".post("edit_publish_time_month")."-".post("edit_publish_time_year")." ".post("edit_publish_time_hour").":".post("edit_publish_time_min") );
				
				$blog->setFeaturedImage( post("featured_image") );
				
				
				$u = new Upload( Upload::getFiles("new_featured_image") );
				
				if( $u->getError() == 0) {
					
					
					if($u->isImage()) {
						$u->forcePath();
						$u->setPath("/images/blog/");
						$u->setName( sha1(strtotime("now"))."_".$u->getName() );
						$u->resize(Settings::GetSetting("BLOG_MAX_IMAGE_WIDTH", 800), Settings::GetSettings("BLOG_MAX_IMAGE_HEIGHT", 600), 80, true);
						if($u->exec()) {
							
							if(file_exists($_SERVER['DOCUMENT_ROOT']."/images/blog/".$blog->getFeaturedImage())) {
								@unlink($_SERVER['DOCUMENT_ROOT']."/images/blog/".$blog->getFeaturedImage());
							}
							
							$blog->setFeaturedImage( $u->getFileName() );
						}
					}
					
				}
				
				if( $blog->save() ) Session::flash("sysmsg", (string)new Success("Blog entry saved successfully"));
				else Session::flash("sysmsg", (string)new Error("Blog entry could not be saved at this time, please try again"));
				
				redirect("/admin/blog");
				
				break;
		}
		
		switch($this->uri->arguments[0]) {
			default:
			case "list":
			case "edit":
			case "new":
				load::view("admin/footer");
			break;
		}
		
	
	}
	
	
	
	public function map() {
		load::model("Lot");
		load::model("MapSystem");
		
		
		
		if(!empty($this->uri->arguments)) {
			
			switch($this->uri->arguments[0]) {
				case "lot":
					if(!isset($this->uri->arguments[1])) redirect('/admin/map');
					$id = $this->uri->arguments[1];
					
					$this->data['title'][] = "Edit";
					$this->data['title'][] = "Lot #$id";
					
					$data['lot'] = new Lot($id);
					$page = "admin/edit.lot";
					
					break;
				case "save":
					// Save what?
					switch($this->uri->arguments[1]) {
						case "lot":
							if(!isset($this->uri->arguments[2])) redirect('/admin/map');
							$id = $this->uri->arguments[2];
							$lot = new Lot($id);
							
							$lot->setName(post("name"));
							$lot->setPrice(post("price"));
							$lot->setLabel(post("label"));
							$lot->setDescription(post("description"));
							
							$lot->setSold( ((post("sold"))?true:false) );
							$lot->setActive( ((post("active"))?true:false) );
							
							$meta = $lot->getMeta();
							$meta->setLabelPoint( new Point( post( "label_x" ), post( "label_y" ) ) );
							$meta->setPricePoint( new Point( post( "price_x" ), post( "price_y" ) ) );
							
							$lot->setMeta($meta);
							
							if($lot->save()) 	Session::flash("sysmsg", (string)new Success("The lot has been saved successfully"));
							else 				Session::flash("sysmsg", (string)new Error("The map could not be saved correctly, please try again"));
							
							redirect("/admin/map");
							die();
							break;
						case "image":
							if(!isset($this->uri->arguments[2])) redirect('/admin/map');
							$id = $this->uri->arguments[2];
							
							$lot = new Lot($id);
							$image = $lot->getImage();
							
							$path = "/map-files/images/";
							
							if($image->getSrc() != null) {
								// do some cleanup
								$existingFilePath = $_SERVER['DOCUMENT_ROOT'].$path.$image->getSrc();
								if(file_exists($existingFilePath)) {
									@unlink($existingFilePath);
								}
							}
							
							//$image->setSrc(  );
							$ul = new Upload( Upload::getFiles("image") );
							if($ul->isImage()) {
								$ul->forcePath();
								$ul->setPath($path);
								$ul->setName($lot->getRegion()."_".$lot->getLot()."_".sha1(strtotime("now"))."_".$ul->getName());
								if($ul->exec()) {
									
									$image->setLotID($lot->getID());
									$image->setSrc($ul->getFileName());
									if($image->save()) Session::flash("sysmsg", (string)new Success("Image Updated Successfully"));
									else Session::flash("sysmsg", (string)new Error("Could not save the image data to the database, but the upload was successful"));
								
									
								} else Session::flash("sysmsg", (string)new Error("Could not upload the new image at this time, please try again"));
							}
							
							
							redirect("/admin/map/lot/".$lot->getID());
							
							die();
							break;
						case "attachment":
							if(!isset($this->uri->arguments[2])) redirect('/admin/map');
							$id = $this->uri->arguments[2];
							
							$lot = new Lot($id);
							$attachment = $lot->getAttachment();
							
							$path = "/map-files/attachments/";
							
							if($attachment->getSrc() != null) {
								// do some cleanup
								$existingFilePath = $_SERVER['DOCUMENT_ROOT'].$path.$attachment->getSrc();
								if(file_exists($existingFilePath)) {
									@unlink($existingFilePath);
								}
							}
							//$image->setSrc(  );
							$ul = new Upload( Upload::getFiles("attachment") );
							$ul->forcePath();
							$ul->setPath($path);
							$ul->setName($lot->getRegion()."_".$lot->getLot()."_".sha1(strtotime("now"))."_".$ul->getName());
							if($ul->exec()) {
								
								$attachment->setLotID($lot->getID());
								$attachment->setType($ul->parseType());
								$attachment->setSrc($ul->getFileName());
								if($attachment->save()) Session::flash("sysmsg", (string)new Success("Attachment Updated Successfully"));
								else Session::flash("sysmsg", (string)new Error("Could not save the attachment data to the database, but the upload was successful"));
							
								
							} else Session::flash("sysmsg", (string)new Error("Could not upload the new attachment at this time, please try again"));
							
							
							redirect("/admin/map/lot/".$lot->getID());
							
							die();
							break;
					}
					
					break;
			}
			
		} else {
			$data['lots'] = Lot::getLots();
			$page = "admin/list.map";
		}
		load::view("admin/header", $this->data);
		load::view($page, $data);
		load::view("admin/footer");
		
	}
	
	
	public function save_settings($system) {
		switch($system) {
			case "map":
				
				/** This is a big one, essentially overwrites as many parameters of the MapSettings class as possible, reserializes and stores it in the db **/
			
				load::model("MapSystem");
				$map = Settings::getMapSettings();
				
				// Lot States
				$map->lot_state_stale = post("lot_state_stale");
				$map->lot_state_hover = post("lot_state_hover");
				$map->lot_state_click = post("lot_state_click");
				$map->lot_state_stale_alpha = post("lot_state_stale_alpha");
				$map->lot_state_hover_alpha = post("lot_state_hover_alpha");
				$map->lot_state_click_alpha = post("lot_state_click_alpha");
				
				// Info Box Settings
				$map->info_box_width = post("info_box_width");
				$map->info_box_height = post("info_box_height");
				$map->info_box_in_time = post("info_box_intime");
				$map->info_box_out_time = post("info_box_outtime");
				$map->info_box_bg_color = post("info_box_bgcolor");
				$map->info_box_box_color = post("info_box_boxcolor");
				$map->info_box_bg_color_alpha = post("info_box_bgcolor_alpha");
				$map->info_box_box_color_alpha = post("info_box_boxcolor_alpha");
				
				if(post("field_type") && count(post("field_type"))>0) {
					for($i = 0; $i < count(post("field_type")); $i++) {
						$field = $map->getField( post("field_type", $i) );
						$field->x = post("field_x", $i);
						$field->y = post("field_y", $i);
						$field->width = post("field_width", $i);
						$field->height = post("field_height", $i);
						
						if($field->type == "image") {
							$field->transition = post("field_other", $i);
						}
						
						$map->saveField($field);
					}
				}
				if(post("font_field") && count(post("font_field"))>0) {
					for($i = 0; $i < count(post("font_field")); $i++) {
						$font = $map->getFont( post("font_field", $i) );
						$font->size = post("font_size", $i);
						$font->color = post("font_color", $i);
						$font->alpha = post("font_color_alpha", $i);
						$font->bold = post("font_bold", $i);
						$font->align = post("font_align", $i);
						$map->saveFont($font);
					}
				}
				
				/*
				Debug::dump(post());
				die(Debug::dump($map));
				*/
				if(Settings::saveMapSettings($map)) {
					Session::flash("sysmsg", (string)new Success("Settings saved successfully"));
				} else {
					Session::flash("sysmsg", (string)new Error("There was an error saving your settings"));
				}
				redirect("/admin/settings/map");
				
			break;
		}
	}
	
	public function slideshow() {
		load::model("Slider");
		
		$this->data['settings'] = Settings::getSliderSettings();
		
		
		if(!empty($this->uri->arguments)) {
				
			switch($this->uri->arguments[0]) {
				
				case "save-settings":
					
					$s = $this->data['settings'];
					$s->setIsOn(post("is_on"));
					$s->setTransition(post("transition"));
					$s->setAnimationSpeed(post("animation_speed"));
					$s->setTransitionSpeed(post("transition_speed"));
					$s->setIsSlideNavOn(post("is_slide_nav_on"));
					$s->setCaptionTransition(post("caption_transition"));
					$s->setCaptionAnimationSpeed(post("caption_animation_speed"));
					$s->setIsSlideNavBulletOn(post("is_slide_nav_bullet_on"));
					$s->setWidth(post("width"));
					$s->setHeight(post("height"));
					
					if(Settings::saveSliderSettings($s)) Session::flash("sysmsg", (string)new Success("Slider settings have been saved successfully"));
					else Session::flash("sysmsg", (string)new Error("Slider settings could not be saved at this time"));
					
					redirect("/admin/slideshow");
					
					break;
					
				case "save-sort":
					
					$json = array("status"=>"false", "msg"=>"");
					
					$slider = null;
					foreach($_POST['order'] as $sort=>$slide) {
						$slider = new Slider($slide);
						$slider->setSort($sort);
						if(!$slider->save()) {
							$json['msg'] = "could not save $slide";
						}
						
					}
					
					if($json['msg'] == "")
						$json['status'] = "true";
					
					echo json_encode($json);
					
					break;
				
				case "save":
					
					if(isset($this->uri->arguments[1])) $slide = new Slider($this->uri->arguments[1]);
					else $slide = new Slider();
					
					$u = new Upload( Upload::getFiles("new_image") );
					if( $u->getError() == 0) {
						
						if($u->isImage()) {
							$u->forcePath();
							$u->setPath("/images/slider/");
							$u->setName( sha1(strtotime("now"))."_".$u->getName() );
							$u->resize( Settings::GetSetting("SLIDER_MAX_IMAGE_WIDTH", 978), Settings::GetSetting("SLIDER_MAX_IMAGE_HEIGHT", 400), 80, false, true);
							if($u->exec()) {
								
								if(file_exists($_SERVER['DOCUMENT_ROOT']."/images/slider/".$slide->getImage())) {
									@unlink($_SERVER['DOCUMENT_ROOT']."/images/slider/".$slide->getImage());
								}
								
								$slide->setImage($u->getFileName());
								
							}
							
							
						}
						
					}
					
					$slide->setCaption( post("caption") );
					$slide->setPublished( ((isset($_POST['published']))?1:0) );
					$slide->setURI(post("url"));
					
					if($slide->save()) Session::flash("sysmsg", (string)new Success("Slide saved successfully"));
					else Session::flash("sysmsg", (string)new Error("Slide could nto be saved at this time"));
					
					redirect("/admin/slideshow");
					
					break;
					
				case "delete":
					
					$slide = new Slider($this->uri->arguments[1]);
					@unlink($_SERVER['DOCUMENT_ROOT']."/images/slider/".$slide->getImage());
					if($slide->delete()) Session::flash("sysmsg", (string)new Success("Slide deleted successfully"));
					else Session::flash("sysmsg", (string)new Error("Slide could not be deleted successfully"));
					
					redirect("/admin/slideshow");
					
					break;
					
			}
			
		} else {
			load::view("admin/header", $this->data);
			$this->data['slides'] = Slider::getSlides(Slider::FILTER_ALL); 
			load::view("admin/list.slideshow", $this->data);
			load::view("admin/footer");
		}
		
		
		
		
		
	}
	
	public function settings($system) {
		load::view("admin/header", $this->data);
		switch($system) {
			case "map":
				load::model("MapSystem");
				$data['map'] = Settings::getMapSettings();
				load::view("admin/settings.map", $data);
				break;
			case "site":
				
				$this->data['social'] = (object)array(
					"Facebook"=>Settings::GetSetting("SM_FACEBOOK"),
					"Twitter"=>Settings::GetSetting("SM_TWITTER"),
					"YouTube"=>Settings::GetSetting("SM_YOUTUBE")
				);
				$this->data['administration'] = (object)array(
					"PostsPerPage"=>Settings::GetSetting("ADMIN_POSTS_PER_PAGE", 5),
					"SiteName"=>Settings::GetSetting("SITE_TITLE", "Default Site Name"),
					"SiteSlogan"=>Settings::GetSetting("SITE_SLOGAN", "Just another Site on the Internet")
				);
				$this->data['front'] = (object)array(
					"PostsPerPage"=>Settings::GetSetting("FRONT_POSTS_PER_PAGE", 5),
					"Favicon"=>Settings::GetSetting("FAVICON_STANDARD"),
					"FaviconIPhone"=>Settings::GetSetting("FAVICON_IPHONE"),
					"FaviconIPhone4"=>Settings::GetSetting("FAVICON_IPHONE4"),
					"FaviconIPad"=>Settings::GetSetting("FAVICON_IPAD")
				);
				
				if(Account::GetData("level") == Account::SUPER_ADMINISTRATOR) {
					$this->data['super'] = (object) array(
						"Blog_MaxImageWidth"=>Settings::GetSetting("BLOG_MAX_IMAGE_WIDTH", 800),
						"Blog_MaxImageHeight"=>Settings::GetSetting("BLOG_MAX_IMAGE_HEIGHT", 600),
						"Gallery_MaxImageWidth"=>Settings::GetSetting("GALLERY_MAX_IMAGE_WIDTH", 1024),
						"Gallery_MaxImageHeight"=>Settings::GetSetting("GALLERY_MAX_IMAGE_HEIGHT", 800),
						"Slider_MaxImageWidth"=>Settings::GetSetting("SLIDER_MAX_IMAGE_WIDTH", 978),
						"Slider_MaxImageHeight"=>Settings::GetSetting("SLIDER_MAX_IMAGE_HEIGHT", 400)
					);
				}
				
				load::view("admin/settings.site", $this->data);
				break;
			case "save-site-settings":
				
				foreach(post() as $key=>$value) Settings::SaveSetting($key, $value);
				
				Session::flash("sysmsg", (string)new Success("Site settings have been saved successfully"));
				
				redirect("/admin/settings/site");
				
				break;
			case "add-field":
				load::model("MapSystem");
				$map = Settings::getMapSettings();
				$map->insertNewField(
					post("field_type"), 
					post("field_x"),
					post("field_y"),
					post("field_width"),
					post("field_height")
				);
				if(Settings::saveMapSettings($map)) {
					Session::flash("sysmsg", (string)new Success("New field added successfully"));
				} else {
					Session::flash("sysmsg", (string)new Error("Could not add field at this time"));
				}
				redirect("/admin/settings/map");
				
				break;
			case "add-font":
				load::model("MapSystem");
				$map = Settings::getMapSettings();
				$map->insertNewFont(
					post("font_field"),
					post("font_size"),
					post("font_color"),
					post("font_alpha"),
					((post("font_bold"))?"true":"false"),
					post("font_align")
				);
				if(Settings::saveMapSettings($map)) {
					Session::flash("sysmsg", (string)new Success("New font added successfully"));
				} else {
					Session::flash("sysmsg", (string)new Error("Could not add font at this time"));
				}
				redirect("/admin/settings/map");
				
				break;
		}
		load::view("admin/footer");
	}
	
	
	
	public function ajax($action) {
		$json = array('status'=>'false', 'msg'=>'');
		switch($action) {
			case "setNewDomainSession":
				$domain = new Domain(post("domain"));
				if($domain->getID()) {
					Session::data("currentDomain", Domain::SetCurrentDomain( post("domain") ));
					$json['status'] = 'true';
				} else {
					$json['msg'] = "Invlid Domain";
				}
				
				break;
			case "save-sort":
				$sort = 1;
				foreach(post("order") as $id) {
					$page = new CMS($id);
					$page->setSort($sort);
					$page->save();
					$sort++;
				}
				$json['status'] = "true";
				break;
			default:
				$json['msg'] = "Invalid Service";
				break;
		}
		echo json_encode($json);
	}

}