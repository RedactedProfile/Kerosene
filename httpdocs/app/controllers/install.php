<?php
class Install {
	
	public function index() {
		/*
		global 
		$needToInstall = true;
		$sql = mysql_query("show tables");
		while($r = mysql_fetch_array($sql)) {
			if(
				$r[0] == "accounts" ||
				$r[0] == "pages" 	||
				$r[0] == "settings"
			) $needToInstall = false;
		}
		
		if($needToInstall) {
			
			redirect("/install/step1");
			
		}
		*/
		redirect("/install/step1");
	}
	
	// step 1: database settings
	// step 2: account settings
	// step 3: base site settings
	
	public function step1() {
		?>
		<?=Session::flash("sysmsg");?>
		<form method="post" action="/install/step1-proceed">
			<h1>Site Settings</h1>
			
			<b>Site Domain (URL):</b><input type="text" name="siteDomain" value="http://www.google.com" /><br />
			<b>Default Controller:</b><input type="text" name="defaultController" value="main" /><br />
			<br />
			<h1>Database Settings</h1>
			
			<b>Connect To Database: </b> <select name="connect">
				<option value="1" selected>Yes</option>
				<option value="2">No</option>
			</select>
			
			<b>Engine: </b><select name="engine">
				<option value="mysql" selected>MySQL</option>
				<!-- <option value="mysqli">MySQLi</option> -->
			</select>
			<br /><br />
			<b>Local (Development):</b><br />
			Host:	<input type="text" name="local_host" value="localhost" /><br />
			Username: <input type="text" name="local_username" value="root" /><br />
			Password: <input type="text" name="local_password" /><br />
			Database: <input type="text" name="local_database" /><br />
			<br />
			<br />
			Condition: <input type="text" name="condition" value=".nav" /><br /> Note: This will check against the URL to see which database configuration to use on the fly. You only need to provide a small chunk, IF IT IS FOUND it will use the local config, else it will switch to Live. 
			<br />
			<br />
			<b>Live (Production):</b><br />
			Host:	<input type="text" name="live_host" value="localhost" /><br />
			Username: <input type="text" name="live_username" /><br />
			Password: <input type="text" name="live_password" /><br />
			Database: <input type="text" name="live_database" /><br />
			<br />
			<input type="submit" value="Create Database" />
		</form>
		<?
	}
	
	public function step1_proceed() {
		// Lets check to see if we can connect with these settings
		
		// do we even want to connect in the first place?
		if($_POST['connect'] == "1") {
			$proceed = false;
			if(@mysql_connect($_POST['local_host'], $_POST['local_username'], $_POST['local_password'])) {
				// ok this is good, but can we select the database?
				if(@mysql_select_db($_POST['local_database'])) {
					// awesome, all checks out here, lets proceed
					$proceed = true;
				} else {
					// damn, so close, take us back to the form
					Session::flash("sysmsg", "Local connection worked, but couldnt select database");
					die("<script>history.go(-1);</script>");
				}
			} else {
				
				// maybe were not on the local server, lets try live?
				if(@mysql_connect($_POST['live_host'], $_POST['live_username'], $_POST['live_password'])) {
					// ok so were live, and everythings chekcing out ok. Can we select the database?
					if(@mysql_select_db($_POST['live_database'])) {
						// woohoo! We are LIVE baby!
						$proceed = true;
					} else {
						// damn, ALMOST GUYS. ALMOST. Kick us back to the form, we can connect but we cant select the db
						Session::flash("sysmsg". "Live connection is correct, but couldnt select database");
						die("<script>history.go(-1);</script>");
					}
				} else {
					// cant connect local, cant connect live. Something is definitely input wrong
					Session::flash("sysmsg", "Neither the Local nor Live settings could connect to any database");
					die("<script>history.go(-1);</script>");
					
				}
				
				
			}
			
		}
		
		
		$config = $_SERVER['DOCUMENT_ROOT']."/app/config/config.php";
		
		
		$settings = '
<?php
/* Base Configurations */


/**
* DefaultController
* This is the controller called when landing on the front page
**/
$Config->DefaultController = "'.$_POST['defaultController'].'";


/**
* Database Configuration
**/
$Config->db->engine = "'.$_POST['engine'].'";
$Config->db->connect = '.(($_POST['connect'] == "1")?"true":"false").';		// Whether to connect or not
$Config->db->condition = "'.$_POST['condition'].'";	// A part of the url to look for to 
									// differentiate between local and live
$Config->db->config = array(		// Local and Live Connection Information
	"local"=>array(
		"Host"=>"'.$_POST['local_host'].'",
		"Username"=>"'.$_POST['local_username'].'",
		"Password"=>"'.$_POST['local_password'].'",
		"Database"=>"'.$_POST['local_database'].'"
	),
	"live"=>array(
		"Host"=>"'.$_POST['live_host'].'",
		"Username"=>"'.$_POST['live_username'].'",
		"Password"=>"'.$_POST['live_password'].'",
		"Database"=>"'.$_POST['live_database'].'"
	)
);


/**
 * The arrays listed below are the main directory search configurations. 
 * You are free to add more, the order matters (top obviusly higher priority) 
 **/
$Config->Bases = array(
		$ApplicationFolder."/bases/"
);
$Config->Controllers = array(
		$ApplicationFolder."/controllers/"
);
$Config->Models = array(
		$ApplicationFolder."/models/",
		$EngineFolder."/models/",
);
$Config->Assistants = array(
		$ApplicationFolder."/assistants/",
		$EngineFolder."/assistants/"
);
$Config->Plugins = array(
		$ApplicationFolder."/plugins/",
		$EngineFolder."/plugins/"
);
$Config->Views = array(
		$ApplicationFolder."/views/"
);


// These pages (based on URI) will be forced to HTTPS mode, all other pages will be forced HTTP
$Config->SecurePages = array(
		
);


/**
* Nevermind These
**/
$Config->DocRoot = $_SERVER[\'DOCUMENT_ROOT\']."/";
$Config->Root = "http://".$_SERVER[\'HTTP_HOST\']."/";
		
		
		';
		
		
		// backup existing config file
		if(file_exists($config)) {
			if(!rename($config, $config.".bak.".strtotime("now"))) {
				Session::flash("sysmsg", "Could not backup existing configuration file, please check folder chmod");
			}
		}
		
		
		$fp = fopen($config, "w");
		if(fwrite($fp, $settings)) {	
			fclose($fp);
			Session::flash("siteDomain", $_POST['siteDomain']);
			redirect("/install/step2");
		} else {
			Session::flash("sysmsg", "COuld not write the new configuration file");
		}
		
		
	}
	
	public function step2() {
		global $Config;
		
		load::model("Account");
		
		if(!$Config->db->connect) {
			// this is a NO DATABASE site, meaning 100% static, lets jsut finish this
			redirect("/install/step5");
			die();
		}
		
		// create the tables
		@mysql_query("drop table `settings`");
		@mysql_query("drop table `accounts`");
		@mysql_query("drop table `pages`");
		@mysql_query("drop table `domains`");
		@mysql_query("drop table `modules`");
		@mysql_query("drop table `themes`");
		
		// settings table
		mysql_query("
			CREATE TABLE `settings` (
				`key`  varchar(255) NULL ,
				`value`  text NULL ,
				PRIMARY KEY (`key`)
			)
		");
		
		// accounts
		mysql_query("
			CREATE TABLE `accounts` (
				`id` int(20) NULL AUTO_INCREMENT ,
				`login` varchar(255) NOT NULL,
				`display` varchar(100) NOT NULL,
				`password` varchar(255) NOT NULL,
				`gravatar` varchar(255) NOT NULL,
				`last_login` datetime NOT NULL,
				`level` int(1) NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `pages` (
				`id` int(11) NULL AUTO_INCREMENT,
				`domain` int(11) NOT NULL,
				`slug` varchar(255) NOT NULL,
				`title` varchar(100) NOT NULL,
				`content` TEXT NOT NULL,
				`category` int(11) NOT NULL DEFAULT 0,
				`meta_title` varchar(100) NOT NULL,
				`meta_keys` varchar(100) NOT NULL,
				`meta_description` varchar(512) NOT NULL,
				`date_added` datetime NOT NULL,
				`date_updated` datetime NOT NULL,
				`display` int(1) NOT NULL DEFAULT 0,
				`sort` int(20) NOT NULL,
				`hits` int(20) NOT NULL DEFAULT 0,
				`unique_hits` int(20) NOT NULL DEFAULT 0,
				`homepage` int(1) NOT NULL DEFAULT 0,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `domains` (
				`id` int(11) NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				`domain` varchar(4) NOT NULL,
				`active` int(1) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `modules` (
				`id` int(11) NULL AUTO_INCREMENT,
				`name` varchar(100) NOT NULL,
				`admin_uri` varchar(255) NOT NULL,
				`active` int(1) NOT NULL DEFAULT 0,
				`required` int(1) NOT NULL DEFAULT 0,
				`visibility` int(3) NOT NULL,
				`protected` int(1) NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `themes` (
				`id` int(3) NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`active` int(1) NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		
		///// THIS IS ALL TEMPORARY UNTIL THE MODULES SYSTEM WORKS
		
		@mysql_query("drop table `blog`");
		@mysql_query("drop table `blog_categories`");
		@mysql_query("drop table `slider`");
		@mysql_query("drop table `gallery`");
		@mysql_query("drop table `gallery_photos`");
		
		mysql_query("
			CREATE TABLE `blog` (
				`id` int(20) NULL AUTO_INCREMENT,
				`category` int(3) NOT NULL DEFAULT 1,
				`slug` varchar(255) NOT NULL,
				`title` varchar(255) NOT NULL,
				`content` text NOT NULL,
				`featured_image` varchar(255) NOT NULL,
				`published` int(1) NOT NULL,
				`meta_title` varchar(255) NOT NULL,
				`meta_keys` varchar(512) NOT NULL,
				`meta_description` varchar(255) NOT NULL,
				`date_published` datetime NOT NULL,
				`date_created` datetime NOT NULL,
				`date_updated` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `blog_categories` (
				`id` int(3) NULL AUTO_INCREMENT,
				`title` varchar(255) NOT NULL,
				`slug` varchar(255) NOT NULL,
				`date_created` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `gallery` (
				`id` int(20) NULL AUTO_INCREMENT,
				`title` varchar(100) NOT NULL,
				`slug` varchar(100) NOT NULL,
				`sort` int(20) NOT NULL,
				`published` int(1) NOT NULL,
				`date_added` datetime NOT NULL,
				`date_updated` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `gallery_photos` (
				`id` int(20) NULL AUTO_INCREMENT,
				`album` int(20) NOT NULL,
				`image` varchar(255) NOT NULL,
				`caption` varchar(255) NOT NULL,
				`sort` int(20) NOT NULL,
				`published` int(1) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		
		mysql_query("
			CREATE TABLE `slider` (
				`id` int(20) NULL AUTO_INCREMENT,
				`img` varchar(255) NOT NULL,
				`caption` varchar(50) NOT NULL,
				`uri` varchar(255) NOT NULL,
				`target` varchar(100) NOT NULL,
				`sort` int(20) NOT NULL,
				`published` int(1) NOT NULL,
				`date_added` datetime NOT NULL,
				`date_updated` datetime NOT NULL,
				PRIMARY KEY (`id`)
			)
		");
		///// /TEMP
		
		
		?>
		<h1>Account Creation</h1>
		<?=Session::flash("sysmsg");?>
		<form method="post" action="/install/step2-proceed">
			<h2>Create Developer Account</h2>
			Email(Login): <input type="text" name="dev_login" /><br />
			DisplayName: <input type="text" name="dev_display" /><br />
			Password: <input type="password" name="dev_password" /><br />
			Gravatar Email: <input type="text" name="dev_gravatar" /><br />
			<h2>Create Administrator Account</h2>
			Email(Login): <input type="text" name="ad_login" /><br />
			DisplayName: <input type="text" name="ad_display" /><br />
			Password: <input type="password" name="ad_password" /><br />
			Gravatar Email: <input type="text" name="ad_gravatar" /><br /><br /><br />
			<input type="submit" />
		</form>
		<?
	}
	
	public function step2_proceed() {
		global $db;
		
		Session::flash("dev_login", $_POST['dev_login']);
		Session::flash("dev_password", $_POST['dev_password']);
		
		// check each field, only gravatar isn't required
		foreach($_POST as $k=>$v) {
			if(!preg_match("/_gravatar/", $k) && trim($v) == "") {
				Session::flash("sysmsg", "All fields except the gravatar field are required");
				die("<script>history.go(-1);</script>");
			}
		}
		
		$db->insert("accounts");
		foreach($_POST as $k=>$v) {
			if(preg_match("/dev_/", $k)) {
				$db->set(preg_replace("/dev_/", "", $k), (($k == "dev_password")? sha1($v) : $v ));
			}
		}
		$db->set("level", 1);
		if($db->exec());
		
		
		$db->insert("accounts");
		foreach($_POST as $k=>$v) {
			if(preg_match("/ad_/", $k)) {
				$db->set(preg_replace("/ad_/", "", $k), (($k == "ad_password")? sha1($v) : $v ));
			}
		}
		$db->set("level", 2)
		->exec();
		
		$db->insert("themes")->set("name", "Nav 1")->set("active", 1)->exec();
		$db->insert("themes")->set("name", "Nav 2")->set("active", 0)->exec();
		$db->insert("themes")->set("name", "Nav 3")->set("active", 0)->exec();
		
		$db->insert("modules")->set("active", 1)->set("protected", 1);
		// required modules
		$required = clone $db;
		$required->set("required", 1);
		$cms = clone $required;
		$domains = clone $required;
		//non-required but engine modules
		$engine = clone $db;
		$blog = clone $engine;
		$slideshow = clone $engine;
		$gallery = clone $engine;
		
		$db->reset();
		
		// use these
		// required
		$cms->set("name", "CMS")->set("admin_uri", "/admin/cms")->set("visibility", Account::ADMINISTRATOR)->exec();
		$domains->set("name", "Domains")->set("admin_uri", "/admin/domains")->set("visibility", Account::SUPER_ADMINISTRATOR)->exec();
		// engine
		$blog->set("name", "Blog")->set("admin_uri", "/admin/blog")->set("visibility", Account::ADMINISTRATOR)->exec();
		$slideshow->set("name", "Slideshow")->set("admin_uri", "/admin/slideshow")->set("visibility", Account::ADMINISTRATOR)->exec();
		$gallery->set("name", "Gallery")->set("admin_uri", "/admin/gallery")->set("visibility", Account::ADMINISTRATOR)->exec();
		
		
		//Domain::SetCurrentDomain();
		$domain = Session::flash("siteDomain");
		//
		$domain = "http://www.kerosene.com";
		Domain::SetCurrentDomain( (($domain)?$domain:null), true ); // if no domain is provided, will create it. If not, will parse the current URL and use that instead
		
		
		// TEMPORARY UNTIL MODULES SYSTEM IS DONE
		
		$db->insert("blog_categories")->set("title", "Uncategories")->set("slug", "uncategorized")->set("date_created", "NOW()", false)->exec();
		
		///
		
		redirect("/install/step3");
	}
	
	public function step3() {
		?>
		<form action="/install/step3-proceed" method="post">
			<h1>Site Settings</h1>
			<?
			$SITE_TITLE = Settings::GetSetting("SITE_TITLE", "My Site");
			$SITE_SLOGAN = Settings::GetSetting("SITE_SLOGAN", "Another Kerosene Powered Site");
			$USE_ANALYTICS = Settings::GetSetting("USE_ANALYTICS", "1");
			$ANALYTICS_CODE = Settings::GetSetting("ANALYTICS_CODE", "");
			$ADMIN_POSTS_PER_PAGE = Settings::GetSetting("ADMIN_POSTS_PER_PAGE", 5);
			$FRONT_POSTS_PER_PAGE = Settings::GetSetting("FRONT_POSTS_PER_PAGE", 5);
			$SM_FACEBOOK = Settings::GetSetting("SM_FACEBOOK", "");
			$SM_TWITTER = Settings::GetSetting("SM_TWITTER", "");
			$SM_YOUTUBE = Settings::GetSetting("SM_YOUTUBE", "");
			$FAVICON_STANDARD = Settings::GetSetting("FAVICON_STANDARD", "/images/favicons/standard.ico");
			$FAVICON_IPHONE = Settings::GetSetting("FAVICON_IPHONE", "/images/favicons/iphone.ico");
			$FAVICON_IPHONE4 = Settings::GetSetting("FAVICON_IPHONE4", "/images/favicons/iphone4.ico");
			$FAVICON_IPAD = Settings::GetSetting("FAVICON_IPAD", "/images/favicons/ipad.ico");
			$SLIDER_MAX_IMAGE_WIDTH = Settings::GetSetting("SLIDER_MAX_IMAGE_WIDTH", 978);
			$SLIDER_MAX_IMAGE_HEIGHT = Settings::GetSetting("SLIDER_MAX_IMAGE_HEIGHT", 400);
			$GALLERY_MAX_IMAGE_WIDTH = Settings::GetSetting("GALLERY_MAX_IMAGE_WIDTH", 1024);
			$GALLERY_MAX_IMAGE_HEIGHT = Settings::GetSetting("GALLERY_MAX_IMAGE_HEIGHT", 800);
			?>
			Site Title: <input type="text" name="SITE_TITLE" value="<?=$SITE_TITLE;?>" /><br />
			Site Slogan: <input type="text" name="SITE_SLOGAN" value="<?=$SITE_SLOGAN;?>" /><br />
			Use Analytics: <input type="text" name="USE_ANALYTICS" value="<?=$USE_ANALYTICS;?>" /><br />
			Analytics Code: <input type="text" name="ANALYTICS_CODE" value="<?=$ANALYTICS_CODE;?>" /><br />
			Admin Posts Per Page: <input type="text" name="ADMIN_POSTS_PER_PAGE" value="<?=$ADMIN_POSTS_PER_PAGE;?>" /><br />
			Front Posts Per page: <input type="text" name="FRONT_POSTS_PER_PAGE" value="<?=$FRONT_POSTS_PER_PAGE;?>" /><br />
			Facebook: <input type="text" name="SM_FACEBOOK" value="<?=$SM_FACEBOOK;?>" /><br />
			Twitter: <input type="text" name="SM_TWITTER" value="<?=$SM_TWITTER;?>" /><br />
			Youtube: <input type="text" name="SM_YOUTUBE" value="<?=$SM_YOUTUBE;?>" /><br />
			Favicon: <input type="text" name="FAVICON_STANDARD" value="<?=$FAVICON_STANDARD;?>" /><br />
			Favicon iPhone: <input type="text" name="FAVICON_IPHONE" value="<?=$FAVICON_IPHONE;?>" /><br />
			Favicon iPhone4: <input type="text" name="FAVICON_IPHONE4" value="<?=$FAVICON_IPHONE4;?>" /><br />
			Favicon iPad: <input type="text" name="FAVICON_IPAD" value="<?=$FAVICON_IPAD;?>" /><br />
			Slider Width: <input type="text" name="SLIDER_MAX_IMAGE_WIDTH" value="<?=$SLIDER_MAX_IMAGE_WIDTH;?>" /><br />
			Slider Height: <input type="text" name="SLIDER_MAX_IMAGE_HEIGHT" value="<?=$SLIDER_MAX_IMAGE_HEIGHT;?>" /><br />
			Gallery Max Width: <input type="text" name="GALLERY_MAX_IMAGE_WIDTH" value="<?=$GALLERY_MAX_IMAGE_WIDTH;?>" /><br />
			Gallery max Height: <input type="text" name="GALLERY_MAX_IMAGE_HEIGHT" value="<?=$GALLERY_MAX_IMAGE_HEIGHT;?>" /><br />
			<br />
			<input type="submit" value="Complete Installation" />
			
		</form>
		<?
	}
	
	public function step3_proceed() {
		global $db;
		foreach($_POST as $k=>$v) Settings::SaveSetting($k, $v);

		$file = $_SERVER['DOCUMENT_ROOT']."/app/controllers/install.php";
		if(@rename($file, $file.".bak.".strtotime("now"))) {
			
			$ac = new Account();
			$ac->setLogin(Session::flash("dev_login"));
			$ac->setPassword(Session::flash("dev_password"));
			if($ac->login()) {
				//if login successful
				redirect("/admin/pages"); // bump over to the pages editor in the admin
			} else {
				redirect("/admin");
			}
		} else {
			die("Could not automatically remove the install script, please manually delete: " . $file);
		}
	}
	
}