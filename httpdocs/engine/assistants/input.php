<?php
function request($key = null) {
	if($key == null) {
		foreach($_REQUEST as &$k) { $k = addslashes(trim($k)); }
		return (object) $_REQUEST;
	}
	if(isset($_REQUEST[$key]))
		return addslashes(trim($_REQUEST[$key]));
	else return false;
}
function post($key = null, $index = -1) {
	if($key == null) {
		foreach($_POST as &$k) { 
			if(is_array($k) || is_object($k)) {										// if an array, we must cleanse each node
				$r = $k;															// temporarily assign
				foreach($r as $key=>$value) $r[$key] = addslashes(trim($value)); 	// cleanse value of temp
				$k = $r;															// reassign to be used
			} else 	$k = addslashes(trim($k)); 
		}
		return (object) $_POST;
	}
	if(isset($_POST[$key]))
		if(is_array($_POST[$key]) || is_object($_POST[$key])) { // cleanse an array
			$ret = $_POST[$key];
			foreach($ret as &$r) $r = addslashes(trim($r));
			return (($index >= 0)? $ret[$index] : $ret );
		} else return addslashes(trim( $_POST[$key] )); // other value
	else return false;
}
function get($key = null) {
	if($key == null) {
		foreach($_GET as &$k) { $k = addslashes(trim($k)); }
		return (object) $_GET;
	}
	if(isset($_GET[$key]))
		return addslashes(trim($_GET[$key]));
	else return false;
}