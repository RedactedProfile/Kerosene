<?
class BaseModel {

	public $db;
	public $uri;
	public $table;
	
	protected $ID_FIELD = "id";
	protected $SLUG_FIELD = "slug";
	
	public function __construct($table = null) {
		global $db, $uri;
		$this->db = $db;
		$this->uri = $uri;
		
		if($table != null) $this->table = $table;
	}
	
	public function fetch($ref) {
	
		if(preg_match("/[a-z]/i", $ref)) $this->db->where($this->SLUG_FIELD, $ref);
		else $this->db->where($this->ID_FIELD, $ref);
		
		$fetch = $this->db->select("*")
						  ->from($this->table)
						  ->get()->result();
		if($this->db->numRows() > 0) 
			foreach($fetch as $k=>$v) 
				$this->{$k} = $v;

	}
	
	public function clean($data) {
		return addslashes(trim($data));
	}
	
	
	public static function FetchAll($table) {
		
	}

}