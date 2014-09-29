<?php namespace Ice;

require_once("resource.class.php");

class Review extends Resource
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }
}

class MerchantReview extends Resource
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }
}
