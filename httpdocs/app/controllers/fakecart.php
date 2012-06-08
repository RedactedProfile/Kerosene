<?php
class fakecart extends BaseController {
	
	public function __construct() {
		parent::__construct();
		load::model("Cart");
		$this->data['cart'] = new Cart();
	}
	
	public function index() {
		?>
		<a href="/fakecart/addProduct/3">Add Adult Novelty Toy</a> | <a href="/fakecart/addProduct/1">Add Hat</a>
		<?
		Debug::dump($this->data['cart']->getCart());
	}
	
	public function addProduct() {
		$this->data['cart']->AddProduct( $this->uri->arguments[0] );
		redirect("/fakecart");
	}
	
}