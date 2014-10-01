<?php namespace Ice;

require_once("resource.class.php");

class User extends Resource
{
	public function __construct($id = null)
	{
		parent::__construct($id);
		$this->getCurrent();
	}

    public function getCurrent()
    {
        if (!$this->_current)
            $this->_current = $this->get("me");
        return $this->_current;
    }

	public function addresses()
	{
		return $this->get("address", array("user" => $this->_current->$id));
	}

	public function applications()
	{
		return $this->get("application", array("contact_user" => $this->_current->$id));
	}

	public function reviews()
	{
		return $this->get("review", array("user" => $this->_current->$id));
	}

	public function profile()
	{
		return $this->get($this->_current."/profile/");
	}

	public function inbox()
	{
		return $this->get($this->_current."/inbox/");
	}
}
