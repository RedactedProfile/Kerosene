
<?php
/* Base Configurations */


/**
* DefaultController
* This is the controller called when landing on the front page
**/
$Config->DefaultController = "main";


/**
* Database Configuration
**/
$Config->db->engine = "mysql";
$Config->db->connect = false;		// Whether to connect or not
$Config->db->condition = "";	// A part of the url to look for to 
									// differentiate between local and live
$Config->db->config = array(		// Local and Live Connection Information
	"local"=>array(
		"Host"=>"",
		"Username"=>"",
		"Password"=>"",
		"Database"=>""
	),
	"live"=>array(
		"Host"=>"",
		"Username"=>"",
		"Password"=>"",
		"Database"=>""
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
$Config->DocRoot = $_SERVER['DOCUMENT_ROOT']."/";
$Config->Root = "http://".$_SERVER['HTTP_HOST']."/";
		
		
		