<?php namespace Ice;

require_once("resource.class.php");

class User extends Resource
{
	public function getAddresses()
	{
		return parent::$Iceberg->get_list("address", null, array("user" => $this->id), "Content-Type:Application/json");
	}

	public function getApplications()
	{
		return parent::$Iceberg->get_list("application", null, array("contact_user" => $this->id), "Content-Type:Application/json");
	}

	public function getReviews()
	{
		return parent::$Iceberg->get_list("review", null, array("user" => $this->id), "Content-Type:Application/json");

	}
	public function getProfile()
	{
		return parent::$Iceberg->get("profile", $this->id."/profile/", null, null, $this->getName());
	}

	public function getInbox()
	{
		return parent::$Iceberg->get_list("inbox", $this->id."/inbox/", null, null, $this->getName());
	}
}

class Inbox extends Resource
{
}

class profile extends Resource
{
}
