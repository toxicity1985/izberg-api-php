<?php namespace Ice;

require_once("resource.class.php");

class Payment extends Resource
{

}

class PaymentCardAlias extends Resource
{
	public function get($user_id, $accept_type = "Accept: application/json")
	{
		$params = array("user" => $user_id);
		return parent::get(null, $params);
	}
}
