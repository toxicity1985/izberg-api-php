<?php namespace Ice;

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
	* Status : confirm | send | cancel
	*
	* @returns object
	*
	**/
	public static function updateStatus($status, $id_order = null)
	{
		if (!$id_order && !$this->_id)
			throw new Exception("No order_id and no URI");
		if ($status != "confirm" && $status != "send" && $status != "cancel")
			throw new Exception("Wrong Status : send | confirm | cancel");
		$id = $id_order ? $id_order : $this->_id;
		return	(self::$Iceberg->Call('merchant_order/'.$id.'/'.$status.'/', 'POST'));
	}
}
