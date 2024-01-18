<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class ResourceTest extends BaseTester
{
	/**
   * @before
   */
  public function startRecording()
  {
    VCR::turnOn();
    VCR::configure()->setStorage('json');
  }

	/**
   * @after
   */
  public function stopRecording()
  {
		// To stop recording requests, eject the cassette
		VCR::eject();
		// Turn off VCR to stop intercepting requests
		VCR::turnOff();
  }

  public function testSaveObject()
	{
    VCR::insertCassette('testSaveObject');

		$a = $this->getIzberg();
		$name = "random description 12345";
		$addresses = $a->get_list('address');
		$address = $addresses[0];
		$address->name = $name;
		$address->save();
		$address_check = $a->get("address", $address->id);
		$this->assertEquals($address->name, $address_check->name);
	}
}
