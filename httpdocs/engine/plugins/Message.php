<?
class Message {
	
	public $wrap = '<div class="sysMsg[CLASS]"><span>[MSG]</span></div>';
	public $class = "";
	public $msg = "";
	
	public function __construct($msg) {
		$this->msg = $msg;
	}
	
	public function __toString() {
		return str_replace(
			array(
				"[MSG]",
				"[CLASS]"
			), 
			array(
				$this->msg,
				" " . $this->class
			),
			$this->wrap
		);
	}
}

class Error extends Message {
	public $class = "error";
	public function __construct($msg) {
		parent::__construct($msg);
	}
	
}

class Success extends Message {
	public $class = "success";
	public function __construct($msg) {
		parent::__construct($msg);
	}
}

class Tip extends Message {
	public $class = "tip";
	public function __construct($msg) {
		parent::__construct($msg);
	}
}