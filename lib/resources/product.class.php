<?php namespace Ice;

require_once "resource.class.php";

class Product extends Resource
{
	public function reviews($id = null)
	{
		if ($id === null && $this->_id)
			$id = $this->_id; 
		return $this->get("review", $params = array("product"=>$id));
	}
}

class ProductOffer extends Resource
{
	public function __construct($id = null)
	{
		$this->setName("productoffer");
		parent::construct($id);
	}
}


class ProductVariation extends Resource
{
}


class OfferImage extends Resource
{
}
