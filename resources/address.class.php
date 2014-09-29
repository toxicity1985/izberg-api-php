<?php namespace Ice;

require_once("resource.class.php");

class Address extends Resource
{
    /* Params:
        address: string
        address2: string
        city: string
        company: string
        country: string
        default_billing: boolean
        default_shipping: boolean
        digicode: string
        first_name: string
        floor: string
        last_name: string
        name: string
        phone: string
        state: string
        status:
          0: Inactive address
          10: Active address
          90: Hidden address
        zipcode: integer
     */

    public function __construct()
    {

        parent::__construct();
    }
}

class MerchantAddress extends Resource
{
    /* Params:
        address: string
        address2: string
        city: string
        company: string
        country: string
        default_billing: boolean
        default_shipping: boolean
        digicode: string
        first_name: string
        floor: string
        last_name: string
        name: string
        phone: string
        state: string
        status:
          0: Inactive address
          10: Active address
          90: Hidden address
        zipcode: integer
     */

    public function __construct()
    {

        parent::__construct();
    }
}
