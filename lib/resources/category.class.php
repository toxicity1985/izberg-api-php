<?php namespace Ice;

require_once("resource.class.php");

class Category extends Resource
{
  private static $resource_endpoint = null;

  public function get_list($params, $accept_type) {
    if (is_null(self::$resource_endpoint) || empty($params)) {
      $response = self::$Izberg->Call("application/" . self::$Izberg->getAppNamespace() . "/locales_config/root_categories/", 'GET', array(), $accept_type);

      // We set the endpoint
      self::$resource_endpoint = $response->meta->resource_endpoint;

      if (empty($params)) return $response;
    }
    // We ask for a child category
    return self::$Izberg->Call(self::$resource_endpoint . "/", 'GET', $params, $accept_type);
  }

  public function get_category_endpoint()
  {
    return self::$resource_endpoint;
  }

  public function get_childs()
  {
    return $this->get_list(array("parents" => $this->id), "Accept: application/json");
  }
}
