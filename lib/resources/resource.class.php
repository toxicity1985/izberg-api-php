<?php namespace Ice;

abstract class Resource
{
	protected static	$Iceberg = null;
	protected			$_name;
	protected			$_id;
	protected			$_uri;

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
	* Hydrate function
	*
	* @return void
	*
	**/
	public function hydrate($obj)
	{
		foreach ($obj as $key=>$value)
			$this->$key = $value;
	}

	/**
	* Deletes Object
	*
	* @return Object
	*
	**/
	public function delete($params = null)
	{
		if (!$this->id)
			throw new Exception(__METHOD__." needs a valid ID");
		$name = $this->getName();
		return self::$Iceberg->Call( $name . "/" . $this->id . "/", 'DELETE', $params, $accept_type);
	}

	/**
	* Updates Object
	*
	* @return Object
	*
	**/
	public function save()
	{
		if (!$this->resource_uri)
			return ;
		$data = (array)$this;
		if (strncmp("http", $data["resource_uri"], 4) == 0)
			$data["resource_uri"] = substr($data["resource_uri"], strlen(self::$Iceberg->getApiUrl()));
		else if ($data["resource_uri"][0] == '/')
			$data["resource_uri"] = substr($data["resource_uri"], 1);
		self::$Iceberg->Call($data["resource_uri"], 'PUT', $data);
	}



}
