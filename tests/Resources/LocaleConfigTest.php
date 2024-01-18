<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class LocaleConfigTest extends BaseTester
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
  public function testGetLocalesConfigShouldReturnLocales()
  {
    VCR::insertCassette('testGetLocalesConfigShouldReturnLocales');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $this->assertEquals($locale->languages, array("fr"));
  }

  public function testAddALocale()
  {
    VCR::insertCassette('testAddALocale');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $locale->update(array("languages" => array("fr","en")));
  }

  public function testDeleteALocale()
  {
    VCR::insertCassette('testDeleteALocale');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $locale->update(array("languages" => array("fr","it")));
    $this->assertEquals($locale->languages, array("fr","it"));
    $locale->delete();
    VCR::insertCassette('testDeleteALocaleBis');
    $locale = $a->get("localeConfig");
    $this->assertEquals($locale->languages, array());
  }
}
