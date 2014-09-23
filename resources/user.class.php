<?php

require_once("resource.class.php");

class iceUser extends iceResource
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_current()
    {
        return $this->get("me");
    }

}
