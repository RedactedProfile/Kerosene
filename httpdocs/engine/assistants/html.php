<?php
function inc($type, $file = null, $folder = null) {
	switch($type) {
		default:
		case "css":
			$folder = (( $folder == null ) ? "/styles/" : $folder );
			echo "<link rel='stylesheet' type='text/css' href='".$folder.$file.".css' />\n";
			break;
		case "js":
			$folder = (( $folder == null ) ? "/scripts/" : $folder );
			echo "<script type='text/javascript' src='".$folder.$file.".js'></script>\n";
			break;
		case "jquery":
		case "jq":
			echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>\n";
			break;
	}
}
