<?php
namespace Izberg\Resource;
use Izberg\Resource;

class Message extends Resource
{
	/*
	**	Mark message as read
	*/
	public function read()
	{
		return self::$Izberg->Call($this->getName()."/read/", "POST");
	}

	/*
	**	Mark message as read
	*/
	public function close()
	{
		return self::$Izberg->Call($this->getName()."/close/", "POST");
	}

	/*
	**	Mark message as read
	*/
	public function all()
	{
		return self::$Izberg->Call($this->getName()."/current_app/all_messages/", "GET");
	}
}
