<?
class Email {

	private $to 		= null;
	private $cc			= null;
	private $bcc		= null;
	private $from 		= null;
	private $subject 	= null;
	private $body		= null;
	
	private $type		= null;
	
	private $headers	= null;

	public function __construct() {
		$this->type("html");
		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
		$this->from = (object) array("Name"=>"[No Name]", "Email"=>"no-reply@".str_replace(array("http://", "www"), "", $_SERVER['HTTP_HOST']));
		$this->subject = "[No Subject]";
		$this->body = "No Message";
	}

	private function build() {
		$headers = array();
		
		$headers[] = "MIME-Version: 1.0";
		
		if( count($this->to) > 0 ) {
			$tos = array();
			foreach($this->to as $to) $tos[] = $to->Name." &lt;".$to->Email."&gt;";
			$headers[] = "To: ".implode(", ", $tos);
		}
		
		if( count($this->cc) > 0 ) {
			$ccs = array();
			foreach($this->cc as $cc) $ccs[] = $cc->Name." &lt;".$cc->Email."&gt;";
			$headers[] = "CC: ".implode(", ", $ccs);
		}
		
		if( count($this->bcc) > 0 ) {
			$bccs = array();
			foreach($this->bcc as $bcc) $bccs[] = $bcc->Name." &lt;".$bcc->Email."&gt;";
			$headers[] = "BCC: ".implode(", ", $bccs);
		}
		
		$headers[] = 'Content-type: '.$this->type.'; charset=iso-8859-1';
	
		$this->headers = implode("\r\n", $headers);
		
		return $this;
	}
	
	public function type($type) {
		switch($type) {
			case "text":
				$this->type = "text/plain";
			break;
			case "html":
				$this->type = "text/html";
			break;
		}
	}
	
	public function to($name, $email) {
		$this->to[] = (object) array("Name"=>$name, "Email"=>$email);
		
		return $this->build();
	}
	
	public function cc($name, $email) {
		$this->cc[] = (object) array("Name"=>$name, "Email"=>$email);
		
		return $this->build();
	}
	
	public function bcc($name, $email) {
		$this->bcc[] = (object) array("Name"=>$name, "Email"=>$email);
		
		return $this->build();
	}
	
	public function subject($subject) {
		$this->subject = trim($subject);
		
		return $this->build();
	}
	
	public function body($body) {
		$this->body = trim($body);
		
		return $this->build();
	}
	
	
	
	
	public function send() {
		
		// As long as we have *someone* to send something too and have a from
		if( (count($this->to) > 0 || count($this->cc) > 0 || count($this->bcc) > 0) ) {
			mail(null, $this->subject, $this->body, $this->headers);
			
			return true;
		} else return false;
		
	}
	
	
}
