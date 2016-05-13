<?php
class productTest extends BaseTester
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
  public function testGetProductShouldReturnProducts()
  {
    \VCR\VCR::insertCassette('testGetProductShouldReturnProducts');

    $a = $this->getIzberg();

    // Check we log request
    $a->expects($this->exactly(2))
      ->method('log');

    $products = $a->get_list("product");
    $this->assertTrue(is_array($products));
    $this->assertNotEmpty($products);
  }

  public function testGetFullProductImportShouldReturnAllProducts()
  {
    \VCR\VCR::insertCassette('testGetFullProductImportShouldReturnAllProducts');

    $a = $this->getIzberg();
    $merchant = $a->get("Merchant", 15);
    $result = $merchant->get_catalog();
    $this->assertInstanceOf('SimpleXMLElement', $result);
  }

  public function testGetProductSchemaShouldReturnProductSchema()
  {
    \VCR\VCR::insertCassette('testGetProductSchemaShouldReturnProductSchema');

    $a = $this->getIzberg();
    $product = $a->get_schema("product");
    $this->assertNotNull($product->allowed_detail_http_methods);
  }

  public function testGetProductOfferShouldReturnProductOffer()
  {
    \VCR\VCR::insertCassette('testGetProductOfferShouldReturnProductOffer');

    $a = $this->getIzberg();
    $product = $a->get("productoffer", 38895);
    $this->assertNotNull($product->id);
  }
}
