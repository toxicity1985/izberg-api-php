<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class AddressTest extends BaseTester
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

  /**
   * Tests
   */
  public function testgetAdressesShouldReturnAdresses()
  {
    VCR::insertCassette('testgetAdressesShouldReturnAdresses');

    $a = $this->getIzberg();
    $addresses = $a->get_list("address");
    $this->assertTrue(is_array($addresses));
  }

  public function testcreateAddressesShouldReturnACreatedAddress()
  {
    VCR::insertCassette('testcreateAddressesShouldReturnACreatedAddress');

    $a = $this->getIzberg();
    $country = $a->get("country");
    $addr = $a->create("address", array(
      "address" => "Address line 1",
      "address2" => "Address line 2",
      "city" => "St remy de provence",
      "company" => "Sebfie",
      "country" => "/v1/country/" . $country->id . "/",
      // "default_billing" => true,
      // "default_shipping" => true,
      "digicode" => null,
      "first_name" => "sebastien",
      "floor" => null,
      "last_name" => "fieloux",
      "name" => "House",
      "phone" => "0698674532",
      "state" => null,
      "status" => 10,
      "zipcode" => "13210"
    ));
    $this->assertArrayHasKey("id", (array) $addr);
    // We check that this address is well linked to the user
    $addresses = $a->get_list("address");
    $this->assertTrue(is_array($addresses));
  }

  public function testGetAddressShouldReturnAddress()
  {
    VCR::insertCassette('testGetAddressShouldReturnAddress');

    $a = $this->getIzberg();
    $country = $a->get("country");
    $addr = $a->create("address", array(
      "address" => "Address line 1",
      "address2" => "Address line 2",
      "city" => "St remy de provence",
      "company" => "Sebfie",
      "country" => "/v1/country/" . $country->id . "/",
      // "default_billing" => true,
      // "default_shipping" => true,
      "digicode" => null,
      "first_name" => "sebastien",
      "floor" => null,
      "last_name" => "fieloux",
      "name" => "House",
      "phone" => "0698674532",
      "state" => null,
      "status" => 10,
      "zipcode" => "13210"
    ));
    // We check that this address is well linked to the user
    $new_address = $a->get("address", $addr->id);
    $this->assertEquals($new_address->id, $addr->id);
  }
}
