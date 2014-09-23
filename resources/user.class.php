<?php
namespace Ice;
require_once("resource.class.php");

class User extends Resource
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
