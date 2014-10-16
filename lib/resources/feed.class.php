<?php namespace Ice;

require_once("resource.class.php");
require_once("merchant.class.php");

class Feed extends Resource
{
    /*
     * @Params :
     *     period: "weeks" | "days" | "hours" | "minutes"
     *     every: integer
     *     name: string
     */

     public function getName($name)
    {
        return "merchant_catalog_feed";
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function post($feed_url, $every, $period, $name, $source_type = "prestashop")
    {
		$merchanthandler = new Merchant();
		$merchant_obj = $merchanthandler->getCurrent();
		if (!$merchant_obj)
			return false;
		$merchant_id = $merchant_obj->objects[0]->id;
        $merchant = "/v1/merchant/".$merchant_id."/";
        $data = array(
                        'merchant'=>$merchant,
                        'every'=>$every,
                        'period'=>$period,
                        'name'=>$name,
                        'source_type'=>$source_type,
                        'feed_url'=>$feed_url
                    );

		return $this->create($data, null, "Content-Type: application/json");
    }

}
