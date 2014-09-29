<?php namespace Ice;

require_once("resource.class.php");

class Cart extends Resource
{

    public function getCurrent()
    {
        if (!$this->_current)
            $_current = $this->get("mine");
        return $this->_current;
    }

    /**
     * get current cart items
     *
     * @return Array
     */
    public function getItems()
    {
        return $this->get($this->getCurrent()->id . "/items");
    }

    /**
     * add an item to a cart
     *
     * @return Array
     */
    public function addItem($params = null, $accept_type = 'Accept: application/json')
    {
        // Params:
        //   offer_id: Integer
        //   variation_id: Integer
        //   quantity: Integer
        //   gift: Boolean
        //   bundled: Boolean
        return $this->create($params, "cart/" . $this->getCurrent()->id . "/items", $accept_type);
    }

    /**
     * update an item to a cart
     *
     * @return Array
     */
    public function updateItem($id, $params = null, $accept_type = 'Accept: application/json')
    {
        // Params:
        //   offer_id: Integer
        //   variation_id: Integer
        //   quantity: Integer
        //   gift: Boolean
        //   bundled: Boolean
        return $this->update($id, $params, "cart_item", $accept_type);
    }

    /**
     * Set cart shipping address
     *
     * @return StdObject
     */
    public function setShippingAddress($id, $params = null)
    {
        $this->getCurrent();
        $params["shipping_address"] = "/v1/address/$id/";
        return $this->update($this->_current->id, $params);
    }


    /**
     * Set cart Billing address
     *
     * @return StdObject
     */
    public function setBillingAddress($id, $params = null)
    {
        $this->getCurrent();
        $params["billing_address"] = "/v1/address/$id/";
        return $this->update($this->_current->id, $params);
    }

    public function createOrder($params = null, $accept_type = 'Accept: application/json')
    {
        return self::$Iceberg->Call("cart/" . $this->_current->id . "/createOrder/", 'POST', $params, $accept_type);
    }

	public function addVariation($product_variation_id, $product_offer_id)
	{
        $params = array(
            'variation_id'=> $product_variation_id,
            'offer_id'=> $product_offer_id,
            'quantity'=> 1
		);            
		return self::$Iceberg->Call($this->getName()."/items/", "POST", $params);
	}

	public function addOffer($product_offer_id)
	{
		/*
        **	Add an offer to the Cart
	 	*/
        $params = array(
            'offer_id'=> $product_offer_id,
            'quantity'=> 1
		);
		return self::$Iceberg->Call($this->getName()."/items/", "POST", $params);
	}

}

class CartItem extends Resource
{
}
