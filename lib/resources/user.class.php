<?php namespace Ice;

require_once("resource.class.php");

class User extends Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->getCurrent();
	}

	public function getCurrent()
	{
		if (!$this->id)
			$object = parent::$Iceberg->get("me");
		return $this;
	}

	public function addresses()
	{
		return $this->get("address", array("user" => $this->$id));
	}

	public function applications()
	{
		return $this->get("application", array("contact_user" => $this->$id));
	}

	public function reviews()
	{
		return $this->get("review", array("user" => $this->$id));
	}

	public function profile()
	{
		return $this->get($this->id."/profile/");
	}

	public function inbox()
	{
		return $this->get($this->id."/inbox/");
	}
}
