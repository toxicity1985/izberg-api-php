<?php

namespace Tests\Resources;

use Tests\BaseTester;

class CountryTest extends BaseTester
{
  /**
   * @before
   */
  public function startRecording()
  {
    \VCR\VCR::turnOn();
    \VCR\VCR::configure()->setStorage('json');
  }

  /**
   * @after
   */
  public function stopRecording()
  {
    // To stop recording requests, eject the cassette
    \VCR\VCR::eject();
    // Turn off VCR to stop intercepting requests
    \VCR\VCR::turnOff();
  }

  /**
   * Tests
   */
  public function testgetCountryShouldReturnTheCountry()
  {
    \VCR\VCR::insertCassette('testgetCountryShouldReturnTheCountry');

    $a = $this->getIzberg();
    $country = $a->get("country", null, array("code" => "IT"));
    $this->assertEquals($country->code, 'IT');
  }
}
