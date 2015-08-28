<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

abstract class BaseTester extends PHPUnit_Framework_TestCase
{

  public function getIzberg($options = array())
	{
		if (empty($options)) {
			$options = array(
					"appNamespace" => "lolote",
					"username" => getenv("USERNAME1"),
					"accessToken" => getenv("TOKEN1"),
					"sandbox" => true
			);
		}
		$mock = $this->getMock('Izberg', array('setTimestamp', 'getTimestamp', 'log'), array($options));

		$mock->expects($this->any())
	    ->method('setTimestamp')
	    ->will($this->returnValue(true));

		$mock->expects($this->any())
	    ->method('getTimestamp')
	    ->will($this->returnValue(1439912480));
		return $mock;
	}

	public function sso(&$a)
	{
		$a->sso(array(
      "email"     => "myemail@yahoo.fr",
			"apiKey"    => "d43fce48-836c-43d3-9ddb-7da2e70af9f1",
			"apiSecret" => "6cb0c550-9686-41af-9b5e-5cf2dc2aa3d0",
      "firstName" => "my_firstname",
      "lastName"  => "my_lastname"
    ));
	}

}
