<?php
class localeConfigTest extends BaseTester
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
  public function testGetLocalesConfigShouldReturnLocales()
  {
    \VCR\VCR::insertCassette('testGetLocalesConfigShouldReturnLocales');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $this->assertEquals($locale->languages, array("fr"));
  }

  public function testAddALocale()
  {
    \VCR\VCR::insertCassette('testAddALocale');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $locale->update(array("languages" => array("fr","en")));
  }

  public function testDeleteALocale()
  {
    \VCR\VCR::insertCassette('testDeleteALocale');

    $a = $this->getIzberg();
    $locale = $a->get("localeConfig");
    $locale->update(array("languages" => array("fr","it")));
    $this->assertEquals($locale->languages, array("fr","it"));
    $locale->delete();
    \VCR\VCR::insertCassette('testDeleteALocaleBis');
    $locale = $a->get("localeConfig");
    $this->assertEquals($locale->languages, array());
  }
}
