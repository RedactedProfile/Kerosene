<?php
class GraphConfig {
	
	const PLACEMENT_BOTTOM 	= 1;
	const PLACEMENT_TOP 	= 2;
	const PLACEMENT_LEFT 	= 1;
	const PLACEMENT_RIGHT 	= 2;
	
	public $name 			= null;
	
	public $graph_width		= null;
	public $graph_height	= null;
	
	public $vert_placement 	= null;
	public $hor_placement	= null;
	
	public $vert_label		= null;
	public $vert_min_value	= null;
	public $vert_max_value 	= null;
	
	public $hor_label 		= null;
	public $hor_min_value 	= null;
	public $hor_max_value 	= null;
	
	public $bgcolor			= null;
	public $fgcolor			= null;
	
	public $filepath		= null;
	public $filename		= null;
	
	public function __construct() {
		$this->setName("Fun Graph");
		$this->setWidth(600);
		$this->setHeight(350);
		$this->setVerticalLabel("Vert Label");
		$this->setVerticalMinValue(0);
		$this->setVeritcalMaxValue(10);
		$this->setHorizontalLabel("Hor Label");
		$this->setHorizontalMinValue(0);
		$this->setHorizontalMaxValue(10);
		$this->setBackgroundColor("#FFFFFF");
		$this->setForegroundColor("#000000");
		$this->setVerticalPlacement(GraphConfig::PLACEMENT_LEFT);
		$this->setHorizontalPlacement(GraphConfig::PLACEMENT_BOTTOM);
		$this->setFilePath("/images/");
		$this->setFilename("graph.png");
	}
	
	public function setName($str) { $this->name = $str; }
	public function setWidth($int) { $this->graph_width = $int; }
	public function setHeight($int) { $this->graph_height = $int; }
	public function setBackgroundColor($hex) { $this->bgcolor = $hex; }
	public function setForegroundColor($hex) { $this->fgcolor = $hex; }
	
	public function setVerticalPlacement($int) { $this->vert_placement = $int; }
	public function setVerticalLabel($str) { $this->vert_label = $str; }
	public function setVerticalMinValue($int) { $this->vert_min_value = $int; }
	public function setVeritcalMaxValue($int) { $this->vert_max_value = $int; }
	
	public function setHorizontalPlacement($int) { $this->hor_placement = $int; }
	public function setHorizontalLabel($str) { $this->hor_label = $str; }
	public function setHorizontalMinValue($int) { $this->hor_min_value = $int; }
	public function setHorizontalMaxValue($int) { $this->hor_max_value = $int; }
	
	public function setFilepath($str) { $this->filepath = $str; }
	public function setFilename($str) { $this->filename = $str; }
	
	
	public function getName() { return $this->name; }
	public function getWidth() { return $this->graph_width; }
	public function getHeight() { return $this->graph_height; }
	public function getBackgroundColor() { return $this->bgcolor; }
	public function getForegroundColor() { return $this->fgcolor; }
	public function getVerticalLabel() { return $this->vert_label; }
	public function getVerticalMinValue() { return $this->vert_min_value; }
	public function getVerticalMaxValue() { return $this->vert_max_value; }
	public function getHorizontalLabel() { return $this->hor_label; }
	public function getHorizontalMinValue() { return $this->hor_min_value; }
	public function getHorizontalMaxValue() { return $this->hor_max_value; }
	
	public function getFilepath() { return $this->filepath; }
	public function getFilename() { return $this->filename ;}
	
	
}
class GraphData {
	private $id		= null;
	private $label	= null;
	private $value 	= null;
	
	private $x		= null;
	private $y		= null;
	
	public function __construct($label, $value) {
		$this->setLabel($label);
		$this->setValue($value);
	}
	
	public function setID($int) { $this->id = $int; return $this; }
	public function setLabel($str) { $this->label = $str; return $this; }
	public function setValue($int) { $this->value = $int; return $this; }
	
	public function setX($int) { $this->x = $int; return $this; }
	public function setY($int) { $this->y = $int; return $this; }
	
	public function getID() { return $this->id; }
	public function getLabel() { return $this->label; }
	public function getValue() { return $this->value; }
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
}
class Graph {
	
	// schema
	
	private $config = null;
	private $data = array();
	
	
	public function __construct($config = null) {
		if($config != null) $this->setConfig($config);
		else $this->setConfig(new GraphConfig());
	}
	
	public function addData($dataObj) {
		if(get_class($dataObj) == "GraphData") {
			$this->data[] = $dataObj;
		} else die("You can only add GraphData objects to the graph");
	}
	
	public function setConfig($config) {
		$this->config = $config;
	}
	
	public function getConfig() { return $this->config; }
	public function getData() { return $this->data; }
	
	public function render() {
		$canvas = new Imagick();
		$canvas->newimage($this->getConfig()->getWidth(), $this->getConfig()->getHeight(), new ImagickPixel( $this->getConfig()->getBackgroundColor() ) );
		
		// draw the graph skeleton
		$skeleton = new ImagickDraw();
		$skeleton->setstrokecolor(new ImagickPixel($this->getConfig()->getForegroundColor()) );
		$offset = 30;
		$skeleton->line( $offset, ($this->getConfig()->getHeight() - $offset), $offset, $offset  );
		$skeleton->line( $offset, ($this->getConfig()->getHeight() - $offset), ($this->getConfig()->getWidth()-$offset) , ($this->getConfig()->getHeight()-$offset) );
		
		/*
		 * Vertical Values
		 */
		$skeleton->settextalignment(IMagick::ALIGN_RIGHT);
		$skeleton->annotation(($offset-5), ($this->getConfig()->getHeight() - $offset), $this->getConfig()->getVerticalMinValue());
		$skeleton->annotation(($offset-5), ($this->getConfig()->getHeight() / 2), ($this->getConfig()->getVerticalMaxValue()/2));
		$skeleton->annotation(($offset-5), $offset, $this->getConfig()->getVerticalMaxValue());
		
		$skeleton->settextalignment(IMagick::ALIGN_CENTER);
		$skeleton->annotation($this->getConfig()->getWidth()/2, 10, $this->getConfig()->getName());
		
		// Add the graph skeleton
		$canvas->drawimage($skeleton);
		
		
		$graph = new ImagickDraw();
		$graph->setstrokecolor(new ImagickPixel( $this->getConfig()->getForegroundColor() ));
		$graph->setfillopacity(0);
		$workingWidth = $this->getConfig()->getWidth() - $offset;
		$stepWidth = $workingWidth / count($this->getData());
		
		$workingHeight = $this->getConfig()->getHeight() - $offset;
		$stepHeight = $workingHeight / $this->getConfig()->getVerticalMaxValue();
		
		$coords = array();
		foreach($this->getData() as $k=>$data) {
			$coords[] = array(
				"x"=>$offset+($k*$stepWidth),
				// experiment 1
				//"y"=>($data->getValue() + $this->getConfig()->getVerticalMaxValue()) + ($this->getConfig()->getHeight() - $offset)
				// experiment 2
				// zero it out, minus the value, scale accordingly
				//"y"=>($this->getConfig()->getHeight()-($offset)) - ( ( $data->getValue() * ($this->getConfig()->getVerticalMaxValue()) - $offset ) * ( $this->getConfig()->getHeight() / 225 ) )
				// 
				"y"=>($this->getConfig()->getVerticalMaxValue() - $data->getValue() ) * $stepHeight
			);
		}
		$graph->polyline($coords);
		
		$canvas->drawimage($graph);
		
		
		
		
		$canvas->setImageFormat( "png" );
		$imagename = $this->getConfig()->getFilepath().$this->getConfig()->getFilename();
		$canvas->writeimage( $_SERVER['DOCUMENT_ROOT'].$imagename );
		return $imagename;
		
	}
	
}