<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class ProductAttributeTest extends BaseTester
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
  public function testGetProductAttributeReturnAttributes()
  {
    VCR::insertCassette('testGetProductAttributeReturnAttributes');

    $a = $this->getIzberg();

    $productAttributes = $a->get_list("productAttribute");
    $this->assertNotEquals(count($productAttributes), 0);
  }
}
