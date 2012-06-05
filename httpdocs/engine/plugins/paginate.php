<?

// Return pagination HTML.
function pagination( $pages, $page, $linkPrefix = NULL, $linkSuffix = NULL ) {
	
	if( $pages == 0 ) return;
	
	// Begin Pagination
	$content = '<div class="pagination">';
	
	// Check if we should display a 'Previous' Button.
	if( $pages > 1 && $page > 1 && $page <= $pages ) {
	
		// Show previous link.
		$content .= '<a class="pg_off" href="'. $linkPrefix . ( $page - 1 ) . $linkSuffix .'">&laquo; Previous</a> ';
			
	}
		
	// Figure out what numbers to display.
	// Ensure we are on a valid page:
	$startingNumber = ( $page <= $pages ) ? max( 1, $page - 2 ) : 1;
	$endingNumber = min( $startingNumber + 4, $pages );

	$count = $startingNumber;
	while( $count <= $endingNumber ) {

		// Output the pages.
		$onOff = ( $count == $page ) ? 'pg_on' : 'pg_off';
		$content .= '<a class="'. $onOff .'" href="'. $linkPrefix . $count . $linkSuffix .'">'. $count .'</a> ';

		// Increment count;
		$count++;
	}
	
	// Check if we should display a 'Next' Button.
	if( $pages > $page ) {

		// Show next link.
		$content .= '<a class="pg_off" href="'. $linkPrefix . ( $page + 1 ) . $linkSuffix .'">Next &raquo;</a> ';
	
	}
	
	// End Pagination
	$content .= '</div>';
	
	return $content;

}

class PaginationPage {
	public $page = 0;
	public $uri = "";
	
	public function __construct($page = null, $uri = null) {
		if($page != null) $this->setPage($page);
		if($uri != null) $this->setUri($uri);
	}
	
	public function setPage($int) { $this->page = $int; }
	public function setUri($str) { $this->uri = $str; }
	public function getPage() { return $this->page; }
	public function getUri() { return $this->uri; }
}
class Paginate {

	const RENDER_ARRAY 				= 1;
	const RENDER_CONDENSED_ARRAY 	= 2;
	const RENDER_HTML				= 3;
	const RENDER_CONDENSED_HTML 	= 4;

	public $records = 0;
	public $perPage = 1;
	public $uriTemplate = "[#]";
	public $currentPage = 1;
	
	public function __construct($records = null, $perPage = null, $uriTemplate = null, $currentPage = null) {
		if($records != null) $this->setRecordCount($records);
		if($perPage != null) $this->setPerPageCount($perPage);
		if($uriTemplate != null) $this->setUriTemplate($uriTemplate);
		if($currentPage != null) $this->setCurrentPage($currentPage);
	}
	
	public function setRecordCount($int) { $this->records = $int; return $this; }
	public function setPerPageCount($int) { $this->perPage = $int; return $this; }
	public function setUriTemplate($str) { $this->uriTemplate = $str; return $this; }
	public function setCurrentPage($int) { $this->currentPage = $int; return $this; }
	public function getRecordCount() { return $this->records; }
	public function getPerPageCount() { return $this->perPage; }
	public function getUriTemplate() { return $this->uriTemplate; }
	public function getCurrentPage() { return $this->currentPage; }
	
	public function parse($page) {
		return str_replace(
			array("[#]"),
			array($page),
			$this->getUriTemplate()
		);
	}
	
	public function render($mode = self::RENDER_ARRAY) {
		$rend = array();
		switch($mode) {
			case self::RENDER_ARRAY:
				
				for($i = 1; $i <= (ceil( $this->getRecordCount()/$this->getPerPageCount() ) ); $i++) {
					$rend[] = new PaginationPage($i, $this->parse($i));
				}
				
				break;
			case self::RENDER_CONDENSED_ARRAY:
				
				
				
				break;
			
			case self::RENDER_HTML:
				
				$arr = $this->render();
				$rend = "<div class='pagination'>";
				foreach($arr as $p) $rend .= "<a class='pagination-page ".(($p->getPage() == $this->getCurrentPage())?"active":null)."' href='".$p->getUri()."'>".$p->getPage()."</a> ";
				$rend .= "</div>";
				break;
		}
		
		return $rend;
	}

}