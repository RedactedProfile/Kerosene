<?
class Session {
	
	public static function kill($data) {
		if(isset($_SESSION[$data])) {
			unset($_SESSION[$data]);
			return true;
		}
		return false;
	}

	public static function data($data, $set = null) {
		if($set != null) {
			$_SESSION[$data] = $set;
			return true;
		}
		if(isset($_SESSION[$data])) return $_SESSION[$data];
		else return false;
	}

	public static function flash($data, $set = null) {
		if($set != null) {
			$_SESSION['flash'][$data] = $set;
			return true;
		}
		if(isset($_SESSION['flash'][$data])) {
			$return = $_SESSION['flash'][$data];
			unset($_SESSION['flash'][$data]);
			return $return;
		} else return false;
	}


}
