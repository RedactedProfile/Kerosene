<?php
/**
* Include Manifest
*/

$autoload = new stdClass();
$route = array();

include($_SERVER['DOCUMENT_ROOT']."/engine/Configuration.php");
Configuration::checkInstall();
$Config = new Configuration();


$includes = array(
		$EngineFolder."/plugins/Consts",
		$EngineFolder."/models/Settings",
		$EngineFolder."/bases/BaseModel",
		$EngineFolder."/bases/BaseController",
		$EngineFolder."/bases/BasePage",
		$EngineFolder."/bases/BaseAdmin",
		$ApplicationFolder."/config/config",
		$ApplicationFolder."/config/autoload",
		$ApplicationFolder."/config/router",
		$EngineFolder."/plugins/load",
		$EngineFolder."/plugins/uri",
		$EngineFolder."/plugins/database",
		$EngineFolder."/plugins/point",
		$EngineFolder."/plugins/upload",
		$EngineFolder."/plugins/paginate",
		$EngineFolder."/plugins/HelloDolly",
		$EngineFolder."/assistants/html",
		$EngineFolder."/assistants/input",
		$EngineFolder."/assistants/system",
		$EngineFolder."/models/CMS",
		$EngineFolder."/models/Domain",
		$EngineFolder."/plugins/debug",
		$EngineFolder."/plugins/session",
		$EngineFolder."/plugins/Tools",
		$EngineFolder."/plugins/Message",
		$EngineFolder."/models/Account"
);
// Loads the includes
foreach($includes as $inc) {
	include($_SERVER['DOCUMENT_ROOT'].$inc.".php");
}



// Required Plugins
//$autoload->Plugins[] = "uri";
//$autoload->Plugins[] = "database";

// Autoload
foreach($autoload as $type=>$list) {
	if(!empty($list)) {
		switch($type) {
			case "Bases":
				$load = "base";
				break;
			case "Models":
				$load = "model";
				break;
			case "Assistants":
				$load = "assistant";
				break;
			case "Plugins":
				$load = "plugin";
				break;
		}
		
		foreach($list as $item) {
			load::$load($item);
		}
	}
}


$uri = new uri();
/**** Secure Force *****/

$HTTP_PORT = intval($_SERVER['SERVER_PORT']);

// boolean, SSL certs wont exist on local testing machines so uses the DB Condition switcher here too
$https_required = ((!preg_match("/".$Config->db->condition."/", $_SERVER['HTTP_HOST']))? in_array($uri->uri, $Config->SecurePages) : false );

if ($https_required && $HTTP_PORT !== 443){
	header('location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit;
}else if(! $https_required && $HTTP_PORT === 443){
	header('location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit;
}

// and here we go


// need to setup router here
foreach($route as $routeFrom=>$routeTo) {
	$routeFrom = explode("/", $routeFrom);
	$routeTo = explode("/", $routeTo);
	if(!$uri->isempty()) {
		if($uri->controller == $routeFrom[0]) {
			$uri->controller = $routeTo[0];
			if($uri->method != null && isset($routeFrom[1]) && $uri->method == $routeFrom[1]) { // routed controller exists
				$uri->method = $routeTo[1];
			}
		}
	}
}


if($uri->isempty()) {
	load::controller($Config->DefaultController);
	$controller = new $Config->DefaultController;
	$controller->index();
} else {
	$controller = null;
	if($uri->controller != null) {
		$uri->controller = str_replace("-", "_", $uri->controller);
		load::controller($uri->controller);
		$controller = new $uri->controller;
	}
	if($uri->method) {
		$uri->method = str_replace("-", "_", $uri->method);
		if(method_exists($controller, $uri->method)) {
			$controller->{$uri->method}( (( count($uri->arguments)>0 )? $uri->attributes() : null ) );
		} else {
			array_unshift($uri->arguments, $uri->method);
			$controller->index( (( count($uri->arguments)>0 )? $uri->attributes() : null ) );
		}
	} else {
		$controller->index();
	}
}
