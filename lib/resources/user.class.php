<?php namespace Ice;

require_once("resource.class.php");

class User extends Resource
{
	public function addresses()
	{
		return $this->get("address", null, array("user" => $this->id));
	}

	public function applications()
	{
		return $this->get("application", null, array("contact_user" => $this->id));
	}

	public function reviews()
	{
		return $this->get("review", null, array("user" => $this->id));
	}

	public function profile()
	{
		return $this->get('profile', $this->id."/profile/", null, null, $this->getName());
	}

	public function inbox()
	{
		return $this->get_list("inbox", $this->id."/inbox/", null, null, $this->getName());
	}
}

class Inbox extends Resource
{
	
}
