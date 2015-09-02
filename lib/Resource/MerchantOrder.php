<?php
namespace Izberg\Resource;
use Izberg\Resource;

class MerchantOrder extends Resource
{
	/**
	* Updates status of existing merchant order
	*
	* Status : confirm | send | cancel
	*
	* @returns object
	*
	**/
	public function updateStatus($status, $id_order = null)
	{
		if (!$id_order && !$this->id)
			throw new Exception("No order_id and no URI");
		$id_order = $id_order ? $id_order : $this->id;
		return	(parent::$Izberg->Call($this->getName().'/'.$id_order.'/'.$status.'/', 'POST'));
	}
}
