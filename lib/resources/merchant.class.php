<?php namespace Ice;

require_once("resource.class.php");

class Merchant extends Resource
{

    public function get_catalog($merchant_id = null, $params = null, $accept_type = 'Accept: application/xml')
    {
        if (!$merchant_id)
            $merchant_id = $this->id;
        return $this->get_list($params, "merchant/".$merchant_id."/download_export", $accept_type);
    }


    public function getCurrent()
    {
		if ($this->_current)
			return $this->_current;
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
			$this->_current = $seller;
        return $seller;
    }
}


class MerchantImage extends Resource
{
}
