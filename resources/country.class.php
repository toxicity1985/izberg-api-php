<?php namespace Ice;

require_once "resource.class.php";

class Country extends Resource
{

    public function get($params = array("code" => "FR"), $accept_type = 'Accept: application/json')
    {
        if (!$this->_current)
            $this->_current = array();
        if (!isset($this->_current[$params["code"]])) {
            $response = parent::get(NULL, $params);
            $result = $response->objects[0];
            $this->_current[$params["code"]] = $result;
        } else {
            $result = $this->_current[$params["code"]];
        }
        return $result;
    }
}
