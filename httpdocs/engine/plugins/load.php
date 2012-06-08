<?php
class load {
	
	public static $views = array();
	
	public static function inc($dirs, $path) {
		global $Config;
		foreach($dirs as $dir) {
			$file = $Config->DocRoot.$dir.$path.".php";
			if(file_exists($file)) {
				include_once($file);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Loads a file that contains a plugin (a class that contains a deal of isolated functionality)
	 * @param unknown_type $name
	 */
	public static function plugin($name) {
		global $Config;
		if(load::inc($Config->Plugins, $name)) return true;
		else return false;
	}
	
	/**
	 * Loads a file that contains a Base (a class that can be globally extended)
	 * @param unknown_type $name
	 */
	public static function base($name) {
		global $Config;
		if(load::inc($Config->Bases, $name)) return true;
		else return false;
	}
	
	/**
	 * Loads a file that contains a series of one or more non-classed globally accessible assisant/helper functions
	 * @param unknown_type $name
	 */
	public static function assistant($name) {
		global $Config;
		if(load::inc($Config->Assistants, $name)) return true;
		else return false;
	}
	
	/**
	 * Loads a model class<br />
	 * A model is a class that gathers information from a data source and provides that information for controllers to use to inject into Views
	 * @param unknown_type $name
	 */
	public static function model($name) {
		global $Config;
		if(load::inc($Config->Models, $name)) return true;
		else return false;
	}
	
	/**
	 * Loads a view file to be compiled into an output
	 * @param unknown_type $name
	 * @param unknown_type $injection
	 */
	public static function view($name, $injection = array()) {
		global $Config, $ApplicationFolder;
		foreach($Config->Views as $dir) {
			$file = $Config->DocRoot.$dir.$name.".php";
			if(file_exists($file)) {
				
				if(is_array($injection) || is_object($injection)) {
					foreach($injection as $k=>$v) {
						$$k = $v;
					}
				}
				
				include($file);
				
				return true;
				
			}
		}
	}
	
	/**
	 * Loads a controller object
	 * @param unknown_type $name
	 */
	public static function controller($name) {
		global $Config;
		if(load::inc($Config->Controllers, $name)) return true;
		else return false;
	}
}

?>
