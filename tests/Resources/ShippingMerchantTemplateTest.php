<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class ShippingMerchantTemplateTest extends BaseTester
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

  public function testGetShippingMerchantTemplate()
	{
		// ini_set("memory_limit","4024M");
    // \VCR\VCR::insertCassette('testGetShippingMerchantTemplate');
		//
		// $a = $this->getShippingIzberg();
		// $merchant_templates = $a->get_list("shippingMerchantTemplate");
    // $this->assertEquals(count($merchant_templates), 0);
	}

}
