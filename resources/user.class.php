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
        if (!$this->_current)
            $this->_current = $this->get("me");
        return $this->_current;
    }

}
