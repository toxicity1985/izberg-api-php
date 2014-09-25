<?php namespace Ice;

require_once("resource.class.php");

class Hook extends Resource
{
    public function __construct()
    {
        parent::__construct();
    }
      /*
        Params:
            application: string
            event: string
            url: string
      */

    public function create($params, $name = null, $accept_type = "Content-Type: application/json")
    {
        parent::create($params, $name, $accept_type);
    }
}
