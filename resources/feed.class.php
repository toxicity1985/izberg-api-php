<?php namespace Ice;

require_once("resource.class.php");

class Feed extends Resource
{
    /*
     * @Params :
     *     period: "weeks" | "days" | "hours" | "minutes"
     *     every: integer
     *     name: string
     */

    private function setName($name)
    {
        $this->_name = "merchant_catalog_feed";
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function post($feed_url, $every, $period, $name, $merchant_id, $souce_type)
    {
        $merchant = "/v1/merchant/".$this->merchant_id."/";
        $data = array(
                        'merchant'=>$merchant,
                        'source_type'=>$source_type,
                        'every'=>$every,
                        'period'=>$period,
                        'name'=>$name,
                        'feed_url'=>$feed_url
                    );

          return $this->create($data, null, "Content-Type: application/json");
    }

}
