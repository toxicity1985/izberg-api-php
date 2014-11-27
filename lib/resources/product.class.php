<?php namespace Ice;

require_once "resource.class.php";

class Product extends Resource
{
	public function reviews($id = null)
	{
		if ($id === null && $this->id)
			$id = $this->id; 
		return parent::$Iceberg->get("review", $params = array("product"=>$id));
	}
}

class ProductOffer extends Resource
{
	public function __construct()
	{
		$this->setName("productoffer");
		parent::construct();
	}
}


class ProductVariation extends Resource
{
}


class OfferImage extends Resource
{
}
