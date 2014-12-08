<?php namespace Ice;

require_once("resource.class.php");

class Merchant extends Resource
{
	public function get_catalog($merchant_id = null, $params = null, $accept_type = 'Accept: application/xml')
	{
		if (!$merchant_id)
			$merchant_id = $this->id;
		return self::$Iceberg->Call("merchant/".$merchant_id."/download_export", 'GET', $params, $accept_type);
	}

	public function getCurrent()
	{
		if ($this->id)
			return $this;
		try
		{
			$seller = parent::$Iceberg->Call('merchant/?api_key='.parent::$Iceberg->getApiKey());
		}
		catch (Exception $e)
		{
			$seller = false;
		}
		if (!isset($seller->meta->total_count))
			$seller = false;
		else if ($seller->meta->total_count == 0)
			$seller = false;
		else
			$this->hydrate($seller->objects[0]);
		return $this;
	}

	public function getCatalog($params = null, $accept_type = 'Accept: application/xml')
	{
		return parent::$Iceberg->Call("merchant/$this->id/download_export/", "GET", $params , $accept_type);
	}
}

class MerchantImage extends Resource
{
}
