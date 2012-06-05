<?
class Tools {

	public static function Slug($string) {
		return preg_replace(
		array(
			"/([^a-z0-9- ]*)/",
			"/ {1,10}/"
		),
		array(
			"",
			"-"
		),
		strtolower( $string )
		);
	}

	public static function ReturnBinaryBool($mixed) {
		$ret = 0;
		if(is_bool($mixed)) {
			$ret = (($mixed == true)? "1":"0");
		} else if(is_int($mixed)) {
			$ret = (($mixed == 1)? "1":"0");
		} else if(is_string($mixed)) {
			$ret = (($mixed == "true" || $mixed == "1")? "1":"0");
		}
		
		return $ret;
	}
	
	
	
	
	const DATEFORMAT_SHORT = "short";
	const DATEFORMAT_MEDIUM = "medium";
	const DATEFORMAT_LONG = "long";
	const DATEFORMAT_MYSQL = "mysql";
	const DATEFORMAT_SHORT_TIME = "short&time";
	const DATEFORMAT_MEDIUM_TIME = "medium&time";
	const DATEFORMAT_RAW = "raw";
	
	public static function FormatDate($template, $str) {
		switch($template) {
			case "short":
				$template = "M d, 'y";
				break;
			case "med":
			case "medium":
				$template = "M jS, Y";
				break;
			case "shorttime":
			case "short_time":
			case "short&time":
			case "short with time":
				$template = "M d, 'y h:i:s";
				break;
			case "medtime":
			case "med_time":
			case "med&time":
			case "med with time":
			case "mediumtime":
			case "medium_time":
			case "medium&time":
			case "medium with time":
				$template = "M jS, Y H:i:s";
				break;
			case "long":
				$template = "F d, Y H:i:s";
				break;
			case "mysql":
				$template = "Y-m-d H:i:s";
				break;
			default:
			case "raw":
				return $str;
				break;
		}
		
		return date($template, strtotime($str));
	}
	
	public static function ConvertDateToMySQL($str) {
		return Tools::FormatDate("mysql", $str);
	}

	
	public static function GetPageRenderStats() {
		global $db;
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $_REQUEST['start']), 4);
		return "Page rendered in ". $total_time.' seconds, with '.$db->count_queries().' queries';
	}
}