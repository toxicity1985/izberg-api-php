<?php
namespace Ice;
require_once("resource.class.php");

class MerchantOrder extends Resource
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Updates status of existing order
     *
     * @returns object
     *
     **/
    public static function updateStatus($id_order, $status)
    {
        return	(self::$Iceberg->Call('merchant_order/'.$id_order.'/'.$status.'/', 'POST'));
    }
}
