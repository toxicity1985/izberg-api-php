<?php

require_once("resource.class.php");

class iceCart extends iceResource
{

    private $_current;

    public function __construct()
    {
        parent::__construct();
    }

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
}
