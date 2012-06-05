<?
class Point {
	public $x = 0;
	public $y = 0;
	
	public function __construct($x = null, $y = null) {
		if($x != null) $this->setX($x);
		if($y != null) $this->setY($y);
	}
	
	public function setX($int) { $this->x = $int; }
	public function setY($int) { $this->y = $int; }
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
 }
 
 class Coords extends Point {
	public function __construct($x = null, $y = null) {
		parent::__construct($x, $y);
	}
 }