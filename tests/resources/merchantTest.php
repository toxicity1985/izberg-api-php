<?php
class merchantTest extends BaseTester
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
  public function testGetMerchantsShouldReturnMerchants()
  {
    \VCR\VCR::insertCassette('testGetMerchantsShouldReturnMerchants');

    $a = $this->getIzberg();
    $merchants = $a->get_list("merchant");
    $this->assertTrue(is_array($merchants));
    $this->assertNotEmpty($merchants);
  }

  public function testGetMerchantsSchemaShouldReturnMerchantsSchema()
  {
    \VCR\VCR::insertCassette('testGetMerchantsSchemaShouldReturnMerchantsSchema');

    $a = $this->getIzberg();
    $merchantSchema = $a->get_schema("merchant");
    $this->assertInstanceOf('stdClass', $merchantSchema);
    $this->assertNotNull($merchantSchema->allowed_detail_http_methods);
  }
}
