<?php namespace Ice;

require_once "resource.class.php";

class Country extends Resource
{

	public function get($params = array("code" => "FR"), $accept_type = 'Accept: application/json')
	{
		$response = parent::get(null, $params);
		$result = $response->objects[0];
		$this->_current = $result;
		return $result;
	}
}
