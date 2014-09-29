<?php namespace Ice;

require_once("resource.class.php");

class Message extends Resource
{
	/*
	**	Mark message as read
	*/
	public function read()
	{
		return self::$Iceberg->Call($this->getName()."/read/", "POST");
	}	
}
