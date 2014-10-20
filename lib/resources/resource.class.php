<?php namespace Ice;

abstract class Resource
{
	protected static $Iceberg = null;

	protected		$_name;
	protected		$_current;
	protected		$_id;
	protected		$_uri;

	public function setCurrent($current)
	{
		$this->_current = $current;
	}

	public function getCurrent()
	{
		return $this->_current;
	}

	private function setName($name = null)
	{
		/*
		** If name isn't specified we convert the class name into ressource name.
		*/
		if ($name === null)
		{
			$name = substr(str_replace("\\", "", get_class($this)), 3);
			$pieces = preg_split('/(?=[A-Z])/',$name);
			$final_str = "";
			foreach ($pieces as $piece)
			{
				if (strlen($final_str) > 0)
					$final_str .= "_";
				$final_str .= strtolower($piece);
			}
		}
		else
			$final_str = $name;
		$this->_name = $final_str;
	}

	public function getName()
	{
		return ($this->_name);
	}

	public function __construct($id = null)
	{
		if (self::$Iceberg === null)
			throw new Exception("Can't create instance of ".get_class().", no valid Iceberg singleton");
		if (!$this->getName())
			$this->setName();
		if ($id)
		{
			$this->get($id);
			$this->_id = $this->_current->id;
			$this->_uri = "/v1/".$this->getName()."/".$this->_id."/";
		}
	}

	/**
	* Every Resource may have the current Iceberg object as singleton
	* In order to contact the API
	*
	* @param Iceberg Object
	*
	**/
	public static function setIceberg($instance)
	{
		self::$Iceberg = $instance;
	}

	/**
	* The Log Function
	*
	* @param string $Message     Your log message
	* @param string [optional]   Log type (default is "ERROR")
	* @param string [optional]   Directory path for logs, CWD by default
	**/
	public function log($message, $level="error", $path = null)
	{
		date_default_timezone_set("Europe/berlin");
		if (false === Iceberg::LOGS)
			return ;
		if (false === is_dir($path))
			$path = null;
		else if ($path && substr($path, -1) != '/')
			$path .= '/';
		file_put_contents($path."log-".$level."-".date("m-d").".txt", date("H:i:s | ")." : ".$message."\n", FILE_APPEND);
	}

	/**
	 * Get Object
	 *
	 * @return Object
	 *
	 **/
	public function get($id = null, $params = null, $name = null, $accept_type = "Accept: application/json")
	{
		if (!$name)
			$name = $this->getName();
		if ($id)
		{
			$this->_id = $id;
			$this->_current = self::$Iceberg->Call($name."/".$id."/", 'GET', $params, $accept_type);
			return $this->_current;
		}
		return self::$Iceberg->Call($name."/", 'GET', $params, $accept_type);
	}

	/**
	 * Get all objects from ressource
	 *
	 * @return Object
	 *
	 **/
	public function get_list($params = null,$name = null, $accept_type = "Accept: application/json")
	{
		if (!$name)
			$name = $this->getName();
		return self::$Iceberg->Call($name."/", 'GET', $params, $accept_type);
	}

	/**
	 * Creates Object
	 *
	 * @return Object
	 *
	 **/
	public function create($params = null,$name = null, $accept_type = "Content-Type: application/json")
	{
		if (!$name)
			$name = $this->getName();
		return self::$Iceberg->Call($name."/", 'POST', $params, $accept_type);
	}

	/**
	 * Updates Object
	 *
	 * @return Object
	 *
	 **/
	public function update($id = null, $params = null, $name = null, $accept_type = "Accept: application/json")
	{
		if (!$id)
			throw new Exception(__METHOD__." needs a valid ID");
		if (!$name)
			$name = $this->getName();
		return self::$Iceberg->Call($name . "/" . $id . "/", 'PUT', $params, $accept_type);
	}

	/**
	 * Deletes Object
	 *
	 * @return Object
	 *
	 **/
	public function delete($id = null, $name = null)
	{
		if (!$id)
			throw new Exception(__METHOD__." needs a valid ID");
		if (!$name)
			$name = $this->getName();
		return self::$Iceberg->Call( $name . "/" . $id . "/", 'DELETE', $params, $accept_type);
	}

	/**
	 * Updates Object
	 *
	 * @return Object
	 *
	 **/
	public function save($data)
	{
		if (!$data || (!$data->resource_uri && !$data["resource_uri"]))
			return ;
		$data = (array)$data;
		if (strncmp("http", $data["resource_uri"], 4) == 0)
			$data["resource_uri"] = substr($data["resource_uri"], strlen(self::$Iceberg->getApiUrl()));
		else if ($data["resource_uri"][0] == '/')
			$data["resource_uri"] = substr($data["resource_uri"], 1);
		return self::$Iceberg->Call($data["resource_uri"], 'PUT', $data);
	}


	public function get_schema($params = null, $name = null, $accept_type = 'Accept: application/json')
	{
	if (!$name)
			$name = $this->getName();
		return $this->get("schema", $params, $name, $accept_type);
	}

}
