<?php
/**
 * 
 * @author KyleHarrison
 *
 */

/**
 * Basic Cart Node
 */
class CartNode { public function __construct() {} }

/**
 * Basic User Information Class
 * Best to extend off of this to create separate information objects
 */
class Information extends CartNode {
	private $first_name	= null;
	private $last_name	= null;
	private $street		= null;
	private $city		= null;
	private $country	= null;
	private $postal		= null;
	private $phone		= null;
	private $cell		= null;
	private $email		= null;
	
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getFirstName() { return $this->first_name; }
	public function getLastName() { return $this->last_name; }
	public function getName() { return $this->getFirstName()." ".$this->getLastName(); }
	public function getStreet() { return $this->street; }
	public function getCity() { return new City($this->getCityID()); }
	public function getCountry() { return new Country($this->getCountryID()); }
	public function getCityID() { return $this->city; }
	public function getCountryID() { return $this->country; }
	public function getPostal() { return $this->postal; }
	public function getMainPhone() { return $this->phone; }
	public function getCellPhone() { return $this->cell; }
	
	
	public function setFirstName($str) { $this->first_name = $str; return $this; }
	public function setLastName($str) { $this->last_name = $str; return $this; }
	public function setName($str) { 
		$name = explode(" ", $str);
		$this->setFirstName( array_shift($name) );
		$this->setLastName( implode(" ", $name) );
		return $this;
	}
	public function setStreet($str) { $this->street = $str; return $this; }
	public function setCity($city) {
		if(Tools::CheckClass($city, "City")) $this->city = $city->getID();
		else $this->city = $city;
		return $this;
	}
	public function setCountry($country) {
		if(Tools::CheckClass($country, "Country")) $this->country = $country->getID();
		else $this->country = $country;
		return $this;
	}
	public function setPostal($str) { $this->postal = $str; return $this; }
	public function setMainPhone($str) { $this->phone = $str; return $this; }
	public function setCellPhone($str) { $this->cell = $str; return $this; }
	
	public function getAsMeta() { return serialize($this); }
}

/**
 * Billing Information<br />
 * extends Information class
 */
class Billing extends Information {
	public function __construct() {
		parent::__construct();
	}
}

/**
 * Shipping Information<br />
 * extends Information class
 */
class Shipping extends Information {
	public function __construct() {
		parent::__construct();
	}
}

class OrderDetails extends CartNode {
	
	
	
	public function __construct() {
		parent::__construct();
	}
	
}

/**
 * The container for the current cart in progress, as well as any existing receipt data
 */
class Receipt extends BaseModel {
	
	public $id				= null;
	public $session			= null;
	public $user			= null;
	public $billing_meta	= null;
	public $shipping_meta	= null;
	public $products_meta	= null;
	public $order_meta		= null;
	public $completed		= null;
	public $date_started	= null;
	public $date_completed	= null;
	
	protected $SLUG_FIELD = "session";
	
	/**
	 * Creates a new Receipt object
	 * @param mixed $ref This is multipurpose, not required.<br /><b>Can accept:</b><br /> - Receipt class<br /> - Standard Class Object<br /> - Receipt ID #<br /> - Receipt Session ID
	 */
	public function __construct($ref = null) {
		if($ref == null) {
			$this->billing_meta	= new Billing();
			$this->shipping_meta = new Shipping();
			$this->products_meta = array();
			$this->order_meta = new OrderDetails();
		}
		
		parent::__construct("cart.receipts");
		
		if($ref != null) {
			if(Tools::CheckClass($ref, "Receipt") || is_object($ref)) {
				foreach($ref as $k=>$v) {
			
					$unserializeMe = array(
						'billing_meta',
						'shipping_meta',
						'products_meta',
						'order_meta'
					);
					
					if(in_array($k, $unserializeMe)) $v = unserialize( stripslashes( $v ) );
					$this->{$k} = $v;
				}
			}
			else $this->fetch($ref);
		}
	}
	
	public function __destruct() {
		$this->save();
	}
	
	
	public function getID() { return $this->id; }
	public function getSession() { return $this->session; }
	public function getUser() { return new Account($this->getUserID()); }
	public function getUserID() { return $this->user; }
	public function getBilling() { return $this->billing_meta; }
	public function getShipping() { return $this->shipping_meta; }
	public function getProducts() { return $this->products_meta; }
	public function getOrder() { return $this->order_meta; }
	public function getCompleted() { return $this->completed; }
	public function isCompleted() { return (($this->getCompleted() == 1)?true:false); }
	public function getDateStarted($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_started); }
	public function getDateCompleted($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_completed); }
	
	public function setSession($str = null) { $this->session = (($str == null) ? session_id() : $str ); return $this; }
	public function setCompleted($bool) { $this->completed = Tools::ReturnBinaryBool($bool); }
	public function setUser($user) {
		if(Tools::CheckClass($user, "Account")) { $this->user = $user->getID(); } 
		else $this->user = $user;
		return $this;
	}
	public function setBillingMeta($billingObj) { 
		if(Tools::CheckClass($billingObj, "Billing")) { $this->billing_meta = $billingObj; }
		return $this;
	}
	public function setShippingMeta($shippingObj) {
		if(Tools::CheckClass($shippingObj, "Shipping")) { $this->shipping_meta = $shippingObj; }
		return $this;
	}
	
	
	
	
	/**
	 * Adds a product to the shopping cart session
	 * @param Product $product a product object
	 * @param int $quantity quantity (optional)
	 */
	public function AddProduct($product, $quantity = 1) {
		if(Tools::CheckClass($product, "Product")) {
			foreach($this->products_meta as &$p) {
				if($product->getID() == $p->getID()) {
					$p->incrementQuantity();
					return true;
				}
			}
			
			$product->setQuantity($quantity);
			$this->products_meta[] = $product;
			return true;
		} else return false;
	}
	
	/**
	 * Updates the quantity of a product already in the shopping cart
	 * @param Product $product a product object
	 * @param int $quantity quantity
	 */
	public function UpdateQuantity($product, $quantity) {
		if(Tools::CheckClass($product, "Product")) {
			foreach($this->products_meta as &$p) {
				if($product->getID() == $p->getID()) {
					$p->setQuantity($quantity);
					return true;
				}
			}
			return false;
		} else return false;
	}
	
	/**
	 * Removes a product from the shopping cart session entirely
	 * @param Product $product a product object
	 */
	public function RemoveProduct($product) {
		if(Tools::CheckClass($product, "Product")) {
			foreach($this->products_meta as $k=>$p) {
				if($product->getID() == $p->getID()) {
					unset($this->products_meta[$k]);
					return true;
				}
			}
			return false;
		} else return false;
	}
	
	
	public function delete() {
		return $this->db->delete($this->table)->where($this->ID_FIELD, $this->getID())->exec();
	}
	
	public function save() {
		
		$products = $this->getProducts();
		foreach($products as &$p) {
			unset($p->db);
			unset($p->uri);
			unset($p->ID_FIELD);
			unset($p->SLUG_FIELD);
		}
		
		
		$this->db->set("session", $this->getSession())
		   ->set("user", $this->getUserID())
		   ->set("billing_meta", mysql_real_escape_string( serialize($this->getBilling())) )
		   ->set("shipping_meta", mysql_real_escape_string( serialize($this->getShipping())) )
		   ->set("products_meta", mysql_real_escape_string( serialize( $products )) )
		   ->set("order_meta", mysql_real_escape_string( serialize($this->getOrder())) )
		   ->set("completed", $this->getCompleted())
		;
		if($this->getID()) $this->db->update($this->table)->where($this->ID_FIELD, $this->getID());
		else $this->db->insert($this->table)->set("date_started", "NOW()", false);
		
		$this->db->exec();
		if($this->db->affectedRows()>0) return true;
		else return false;
	}
	
	public static function GetCart($session) {
		global $db;
		$fetch = $db->select("*")->from("cart.receipts")->where("session", $session)->where("completed", "0")->get()->result();
		if($db->numrows()>0) return new Receipt( $fetch );
		else return new Receipt();
	}
}

class ProductType extends BaseModel {
	public $id			= null;
	public $name		= null;
	public $slug		= null;
	public $sort		= null;
	public $date_added	= null;
	
	public function __construct($ref = null) {
		parent::__construct("cart.products.type");
		if($ref != null) {
			if(Tools::CheckClass($ref, "ProductType") || is_object($ref)) {
				foreach($ref as $k=>$v) $this->{$k} = $v;
			} else $this->fetch($ref);
		}
	}
	
	public function getID() { return $this->id; }
	public function getName() { return $this->name; }
	public function getSlug() { return $this->slug; }
	public function getSort() { return $this->sort; }
	public function getDateAdded($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_added); }
	
	public function setName($str) { $this->name = $str; return $this; }
	public function setSlug($str) { $this->slug = $str; return $this; }
	public function setSort($int) { $this->sort = $int; return $this; }
	
	public function delete() {
		global $db;
		return $db->delete($this->table)->where($this->ID_FIELD, $this->getID())->exec();
	}
	
	public function save() {
		global $db;
		$fields = array(
			"name"=>$this->getName(),
			"slug"=>$this->getSlug(),
			"sort"=>$this->getSort(),
		);
		foreach($fields as $k=>$v) $db->set($k, $v);
		
		if($this->getID()) $db->update($this->table);
		else $db->insert($this->table)->set("date_added", "NOW()", false);
		$db->exec();
		if($db->affectedRows()>0) return true;
		else return false;
	}
}

class Product extends BaseModel {
	public $id			= null;
	public $type		= null;
	public $name		= null;
	public $slug		= null;
	public $description	= null;
	public $price		= null;
	public $photo		= null;
	public $sort		= null;
	public $visible		= null;
	public $special		= null;
	public $date_added	= null;
	
	public $quantity	= 0;
	
	public function __construct($ref = null) {
		parent::__construct("cart.products");
		if($ref != null) {
			if(Tools::CheckClass($ref, "Product") || is_object($ref)) {
				foreach($ref as $k=>$v) $this->{$k} = $v;
			} else $this->fetch($ref);
		}
	}
	
	public function getID() { return $this->id; }
	public function getProductType() { return new ProductType($this->getProductTypeID()); }
	public function getProductTypeID() { return $this->type; }
	public function getName() { return $this->name; }
	public function getSlug() { return $this->slug; }
	public function getDescription() { return $this->description; }
	public function getPrice($format = false) { return (($format)?number_format($this->price, 2):$this->price); }
	public function getPhoto() { return $this->photo; }
	public function getSort() { return $this->sort; }
	public function getVisible() { return $this->visible; }
	public function isVisible() { return (($this->getVisible()==1)?true:false); }
	public function getSpecial() { return $this->special; }
	public function isSpecial() { return (($this->getSpecial()==1)?true:false); }
	public function getDateAdded($format = DateFormat::RAW) { return Tools::FormatDate($format, $this->date_added); }
	
	public function setProductType($type) {
		if(Tools::CheckClass($type, "ProductType")) $this->type = $type->getID();
		else $this->type = $type;
		
		return $this;
	}
	public function setName($str) { $this->name = $str; return $this; }
	public function setSlug($str) { $this->slug = $str; return $this; }
	public function setDescription($str) { $this->description = $str; return $this; }
	public function setPrice($float) { $this->price = $float; return $this; }
	public function setPhoto($str) { $this->photo = $str; return $this; }
	public function setSort($int) { $this->sort = $int; return $this; }
	public function setVisible($bool) { $this->visible = Tools::ReturnBinaryBool($bool); return $this; }
	public function setSpecial($bool) { $this->special = Tools::ReturnBinaryBool($bool); return $this; }

	// Quantity
	public function getQuantity() { return $this->quantity; }
	public function setQuantity($int) { $this->quantity = $int; return $this; }
	public function incrementQuantity() { $this->quantity++; return $this; }
	public function decrementQuantity() { $this->quantity--; return $this; }
	/////////////
	
	public function delete() {
		global $db;
		return $db->delete($this->table)->where($this->ID_FIELD, $this->getID())->exec();
	}
	
	public function save() {
		global $db;
		$fields = array(
			"type"=>$this->getProductTypeID(),
			"name"=>$this->getName(),
			"slug"=>$this->getSlug(),
			"description"=>$this->getDescription(),
			"price"=>$this->getPrice(),
			"photo"=>$this->getPhoto(),
			"sort"=>$this->getSort(),
			"visible"=>$this->getVisible(),
			"special"=>$this->getSpecial()
		);
		foreach($fields as $k=>$v) $db->set($k, $v);
		
		if($this->getID()) $db->update($this->table);
		else $db->insert($this->table)->set("date_added", "NOW()", false);
		$db->exec();
		if($db->affectedRows()>0) return true;
		else return false;
	}
}

/**
 * Wrapper for the entire Cart System
 */
class Cart extends CartNode {
	
	public $session = null;
	public $cart 	= null;
	
	/**
	 * Creates a new cart, then checks if theres a cart in progress
	 */
	public function __construct() {
		$this->session = session_id();
		$this->cart = Receipt::GetCart($this->session);
		if(!$this->cart->getID()) {
			$this->cart->setSession($this->session);
		}
		
	}
	
	public function getCart() { 
		return $this->cart;
	}
	
	public function AddProduct($product) {
		$this->cart->AddProduct( new Product($product) );
	}
	
	public function saveCart($cart) {
	}
	
}