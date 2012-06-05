<?
class Settings {

	const SETTING_SITE_TITLE = "SITE_TITLE";
	const FAVICON_STANDARD = "FAVICON_STANDARD";
	const FAVICON_IPHONE = "FAVICON_IPHONE";
	const FAVICON_IPHONE4 = "FAVICON_IPHONE4";
	const FAVICON_IPAD = "FAVICON_IPAD";
	const FRONT_POSTS_PER_PAGE = "FRONT_POSTS_PER_PAGE";
	
	/*
	 ****************************************************************
	 ***********   Basic Setting Getters and Savers    **************
	 ****************************************************************
	*/

	/**
	 * Will attempt to collect a setting from the settings table in the database. 
	 * If the key cannot be found, a blank value will be created for you, yet will return false
	 * regardless.
	 * @param str $key The setting
	 * @param mixed $default A default value to impose if the setting does not exist
	 */
	public static function GetSetting($key, $default = null) {
		global $db;
		$val;
		$fetch = $db->select("value")->from("settings")->where("key", $key)->get()->result();
		if($db->numRows() > 0) $val = $fetch->value;
		else {
			$db->insert("settings")->set("key", $key)->set("value", $default)->exec();
			$val = $default;
		}
		return $val;
	}
	
	public static function SaveSetting($key, $value) {
		global $db;
		if($db->update("settings")->set("value", $value)->where("key", $key)->exec()) return true;
		else return false;
	}

	
	/*
	 ****************************************************************
	 ************   Map System Specific Settings ********************
	 ****************************************************************
	*/
	
	
	public static $MapFields = array(
		"title"=>"Title",
		"price"=>"Price",
		"status"=>"Status",
		"description"=>"Description",
		"image"=>"Image",
		"attachment"=>"Attachment"
	);
	
	public static $FontFields = array(
		"label"=>"Map: Label",
		"sold"=>"Map: Status",
		"price"=>"Map: Price",
		"info_description"=>"Info Box: Description",
		"info_title"=>"Info Box: Title",
		"info_status"=>"Info Box: Status",
		"info_price"=>"Info Box: Price"
	);
	
	public static function getMapSettings() {
		global $db;
		$settings = Settings::GetSetting("MAP_SETTINGS");
		if($settings == "" || $settings == null) {
			$settings = new MapSettings();
			Settings::SaveSetting("MAP_SETTINGS", serialize($settings));
		}
		else $settings = unserialize( $settings );
		
		return $settings;
	}
	
	public static function saveMapSettings($map) {
		global $db;
		$sql = $db->update("settings")->set("value", serialize($map))->where("key", "MAP_SETTINGS")->exec();
		if($db->affectedRows() > 0) return true;
		else return false;
	}
	
	// calc the diff between used map fields and still available
	public static function getAvailableMapFields() {
		$map = Settings::getMapSettings();
		$fields = $map->getField();
		$used = array();
		foreach(Settings::$MapFields as $k=>$available) {
			foreach($fields as $f) {
				if($k == $f->type) {
					$used[] = $f->type;
				}
			}
		}
		$avail = Settings::$MapFields;
		foreach($used as $u) {
			unset($avail[$u]);
		}
	
		return $avail;
	}
	
	public static function getAvailableFontFields() {
		$map = Settings::getMapSettings();
		$fields = $map->getFont();
		$used = array();
		foreach(Settings::$FontFields as $k=>$available) {
			foreach($fields as $f) {
				if($k == $f->field) {
					$used[] = $f->field;
				}
			}
		}
		$avail = Settings::$FontFields;
		foreach($used as $u) {
			unset($avail[$u]);
		}
	
		return $avail;
	}
	
	
	
	
	/*
	 ****************************************************************
	 ****************   Slider Specific Settings ********************
	 ****************************************************************
	*/
	
	
	public static function getSliderSettings() {
		global $db;
		
		$settings = Settings::GetSetting("SLIDER_SETTINGS");
		if($settings == "" || $settings == null) {
			$settings = new Slider_Setting();
			Settings::SaveSetting("SLIDER_SETTINGS", serialize($settings));
		}
		else $settings = unserialize($settings);
		
		return $settings;
	}
	
	public static function saveSliderSettings($slider) {
		global $db;
		$sql = $db->update("settings")->set("value", serialize($slider))->where("key", "SLIDER_SETTINGS")->exec();
		if($db->affectedRows() > 0) return true;
		else return false;
	}
	
	
	
	
}