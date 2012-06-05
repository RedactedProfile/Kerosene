<?
/**
 * <b>DatabaseDriver</b><br /><i>A standalone CodeIgniter "ActiveRecord" inspired set of query handling and manipulation.
 * @author Kyle Harrison &lt;silent.coyote1@gmail.com&gt;
 * @copyright Black Jaguar Studios 2011
 * @package JagLIBPHP
 * @license DBAD License 0.1: Commercial and Non-Commercial usage without explicit permission allowed, just say "thanks" if you get the opportunity :)
*/
class DatabaseDriver {
	
	private $mode; // select, insert, update, delete
	private $select;
	private $distinct = false;
	private $from = array();
	private $where = array();
	private $orwhere = array();
	private $join = array();
	private $set = array();
	private $orderby = array();
	private $limit;

	private $driver;
	
	private $active_row = 0; // Determins which result in the results array is currently active

	public $query;
	public $results;
	public $rows;
	public $affected_rows;
	public $insert_id;
	
	public $queries = array();
	
	public function __construct($engine = "mysql") {
		$this->driver = $engine;
	}
	
	public function select($cols = "*") {
		
		$this->mode = "select";
	
		if(is_array($cols)) {
			foreach($cols as &$c) $c = "`".$c."`";
			$cols = implode(", ", $cols);
		} else {
			
		}
		
		$this->select = $cols;
		
		$this->build_query();
		return $this;
	}

	public function distinct() {
		$this->distinct = true;
		$this->build_query();
		return $this;
	}
	
	public function insert($table = null, $set = null) {
		$this->mode = "insert";
		
		if($table != null) $this->from($table);
		
		if($set != null) $this->set($set);
		
		$this->build_query();
		return $this;
	}
	
	public function update($table = null, $set = null) {
		$this->mode = "update";
		
		if($table != null) $this->from($table);
		
		if($set != null) $this->set($set);
		
		$this->build_query();
		return $this;
	}
	
	public function delete($table = null, $set = null) {
		$this->mode = "delete";
		
		if($table != null) $this->from($table);
		
		if($set != null) $this->set($set);
		
		$this->build_query();
		return $this;
	}
	
	public function from($table, $alias = null) {
		$this->from[] = "`$table`".(($alias != null)?" as `$alias`":"");
		
		$this->build_query();
		return $this;
	}
	
	// From alias
	public function table($table, $alias = null) {
		$this->from($table, $alias);
		return $this;
	}
	
	// From alias
	public function into($table, $alias = null) {
		$this->from($table, $alias);
		return $this;
	}
	
	// parses a list of setable objects
	public function set($data, $value = null, $escape = true) {
		if(is_array($data)) {
			foreach($data as $k=>$d) {
				$this->set[] = (($escape == true) ? "`".$k."` = '".addslashes($d)."'" : "`".$k."` = ".addslashes($d) );
			}
			$this->build_query();
			return $this;
		}
		
		$this->set[] = (($escape == true) ? "`".$data."` = '".addslashes($value)."'" : "`".$data."` = ".addslashes($value) );
		$this->build_query();
		return $this;
	}
	
	public function where($col, $clause, $override = "=", $escape = true) {
		//$this->where[] = "`$col` $override '$clause'";
		$this->where[] = "`$col` $override ".(($escape)?"'$clause'":"$clause");
		
		$this->build_query();
		return $this;
	}
	
	public function orwhere($col, $clause, $override = "=", $escape = true) {
		$this->where[] = "[OR]`$col` $override ".(($escape)?"'$clause'":"$clause");
		
		$this->build_query();
		return $this;
	}
	
public function wherelike($col, $clause, $dir = "both") {
		switch($dir) {
			default:
			case "both":
				$clause = "%$clause%";
				break;
			case "left":
				$clause = "%$clause";
				break;
			case "right":
				$clause = "$clause%";
				break;
			case "strict":
				// Do nothing, No Wild Cards
				break;
		}
		$this->where[] = "`$col` LIKE '$clause'";
	
		$this->build_query();
		return $this;
	}
	
	public function orwherelike($col, $clause, $dir = "both") {
		switch($dir) {
			default:
			case "both":
				$clause = "%$clause%";
				break;
			case "left":
				$clause = "%$clause";
				break;
			case "right":
				$clause = "$clause%";
				break;
			case "strict":
				// Do nothing, No Wild Cards
				break;
		}
		$this->where[] = "[OR]`$col` LIKE '$clause'";
	
		$this->build_query();
		return $this;
	}
	
	public function join($table, $alias, $on_original, $on_compare, $type = "left") {
		
		switch($type) {
			default:
			case "left":
				$type = "LEFT JOIN";
			break;
			case "right":
				$type = "RIGHT JOIN";
			break;
			case "inner":
				$type = "INNER JOIN";
			break;
			case "outer":
				$type = "OUTER JOIN";
			break;
		}
		
		if($alias != "" && $alias != null) $alias = "as `$alias`";
		
		$on = "ON `$on_original` = `$on_compare`";
		
		$this->join[] = "$type `$table` $alias $on";
	
		$this->build_query();
		return $this;
	}
	
	/**
	 * Orderby Override, allows complete free text for orderby clause
	 * @param string $str
	 */
	public function orderby_override($str) {
		$this->orderby[] = $str;
		$this->build_query();
		return $this;
	}
	
	public function orderby($col, $dir = null) {
		$data = explode(" ", $col);
		if(count($data)>1) {
			$col = "`".$data[0]."` ".$data[1];
		}
		
		$order = $col;
		
		if(trim($order) != "" && $dir == null) $dir = " ASC";
		
		if($dir != null) $order .= " $dir";
		
		$this->orderby[] = $order;
		
		$this->build_query();
		return $this;
	}
	
	// Orderby Alias
	public function order($col, $dir = null) {
		$this->orderby($col, $dir);
	}
	
	
	public function limit($num) {
		$this->limit = $num;
		$this->build_query();
		return $this;
	}
	
	
	public function build_query() {
		$query = array();
		
		switch($this->mode) {
			case "select":
			default:
				
				$query[] = "SELECT".(($this->distinct)?" DISTINCT":"")." ".$this->select;
		
				if(count($this->from) >= 1)
					$query[] = "FROM ".implode(" AND ", $this->from);
				
				if(count($this->join) >= 1)
					$query[] = implode(" ", $this->join);
				
				if(count($this->where) >= 1)
					$query[] = "WHERE " . implode(" AND ", $this->where);
				
				if(count($this->orderby) >= 1)
					$query[] = "ORDER BY " . implode(", ", $this->orderby);
				
				if($this->limit != null)
					$query[] = "LIMIT ".$this->limit;
				
				break;
			case "insert":
				$query[] = "INSERT INTO " .((isset($this->from[0]))?$this->from[0]:""). " SET";
				$query[] = implode(", ", $this->set);

				break;
			case "delete":
				$query[] = "DELETE FROM ".((isset($this->from[0]))?$this->from[0]:"");
				if(count($this->where) >= 1)
					$query[] = "WHERE " . implode(" AND ", $this->where);
					
				break;
			case "update":
				$query[] = "UPDATE " .((isset($this->from[0]))?$this->from[0]:""). " SET" ;
				$query[] = implode(", ", $this->set);
				if(count($this->where) >= 1)
					$query[] = "WHERE " . implode(" AND ", $this->where);
				break;
		}
		
	
		$this->query = implode(" ", $query);
		$this->query = preg_replace("/AND \[OR\]/", "OR ", $this->query); // added for makeshift OR clause
		
		return $this;
	}
	
	// Executes the built query
	public function exec() {
		
		if($sql = @mysql_query($this->query)) {
			$this->flush();
			
			if($this->mode == "select") $this->rows = mysql_num_rows($sql);
			
			$this->affected_rows = mysql_affected_rows();
			
			if($this->mode == "select") {
				while($r = mysql_fetch_object($sql)) {
					$this->results[] = $r;
				}
			} else {
				// insert and update modes dont fetch results
				$this->results[] = true;
			}
			if($this->mode == "insert") $this->insert_id = mysql_insert_id();
			
			$this->queries[] = $this->query;
			$this->reset();
			return $this;
		} else {
			$this->results[] = false;
			die("<span style='color: red'>There was an error running your query:</span><br />". mysql_error() . "<hr /><pre>".$this->query."</pre>");
		}
	}
	
	// Alias for exec()
	public function get() {
		$this->exec();
		return $this;
	}
	
	// Alias for exec()
	public function run() {
		$this->exec();
		return $this;
	}
	
	// Returns all results
	public function results() {
		return $this->results;
	}
	
	// gets specific row index. if no number is provided gets the top result
	public function result($row = 0) {
		return $this->results[$row];
	}
	
	// alias for "result"
	public function row($row = null) {
		return $this->result($row);
	}
	
	// get first NUM available rows
	public function rows($rows = 1) {
		
	}
	
	public function next() {
		$active = $this->active_row;
		if((count($this->results) - 1) >= ($active + 1)) {
			$this->active_row++;
		}
		
		return $this->row($this->active_row);
	}
	
	public function prev() {
		$active = $this->active_row;
		if((count($this->results) - 1) >= ($active - 1)) {
			$this->active_row--;
		}
		
		return $this->row($this->active_row);
	}
	
	
	// alias for next()
	public function step() {
		return $this->next();
	}
	
	// alias for prev()
	public function back() {
		return $this->prev();
	}
	
	// flush results
	public function flush() {
		
		$this->results = null;
		
		return $this;
	}
	
	// resets the driver
	public function reset() {
		$this->select = null;
		$this->from = array();
		$this->where = array();
		$this->join = array();
		$this->set = array();
		$this->orderby = null;
		$this->limit = null;
		$this->active_row = 0;
		$this->query = null;
		
		return $this;
	}
	
	
	// Ohter fun functions
	
	public function numrows() {
		return $this->rows;
	}

	public function affectedRows() {
		return $this->affected_rows;
	}
	
	public function count_queries() {
		return count($this->queries);
	} 
	
	public function get_query($index = 0) {
		return $this->queries[$index];
	}
	
	public function insertID() {
		return $this->insert_id;
	}
	
	public static function Connect($settings, $local_condition = ".dev") {
		$env = ( ( preg_match( "/".str_replace('.', '\.', $local_condition."/"), $_SERVER['HTTP_HOST'] ) ) ? "local" : "live" );
		$s = (object) $settings[$env];
		mysql_connect($s->Host, $s->Username, $s->Password);
		mysql_select_db($s->Database);
	}
	
}

if($Config->db->connect) {
	DatabaseDriver::Connect($Config->db->config, $Config->db->condition);
}
$db = new DatabaseDriver("mysql");
