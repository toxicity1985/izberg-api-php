<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class ShippingProviderAssignmentTest extends BaseTester
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

  public function testGetShippingProviderAssignments()
	{
    VCR::insertCassette('testGetShippingProviderAssignments');

		$a = $this->getShippingIzberg();
		$provider_assignments = $a->get_list("shippingProviderAssignment");
    $this->assertEquals(count($provider_assignments), 2);
	}

  public function testCreateShippingProviderAssignments()
	{
    VCR::insertCassette('testCreateShippingProviderAssignments');

		$a = $this->getShippingIzberg();
		$provider_assignment = $a->create("shippingProviderAssignment",array(
			"merchant" => "/v1/merchant/1168/",
			"provider" => "/v1/shipping_provider/27/",
			"options" => array()
		));
    $this->assertNotNull($provider_assignment->id);
	}

}
