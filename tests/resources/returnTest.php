<?php
class returnTest extends BaseTester
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
  public function testCreateAReturn()
  {
    \VCR\VCR::insertCassette('testCreateAReturn');

    $a = $this->getIzberg();
    $user = $a->get("user");

    // We get an existing order
    $merchant_order = $a->get("merchantOrder", 871);
    $item = $merchant_order->items[0];

    // We return this item
    $merchant_order = $merchant_order->createReturn(array(
      "order_items" => array($item->id),
      "return_type" => 1
    ));

    // We get the return
    $return = $a->get("return", $merchant_order->created_return_request_ids[0]);

    $this->assertNotNull($return->id);
    $this->assertEquals("open", $return->status);

    // Then we accept it
    $return = $return->accept();
    $this->assertEquals("accepted", $return->status);

    // Then we mark it as received
    $return = $return->received();
    $this->assertEquals("package_received", $return->status);

    // Then we close it
    $return = $return->close();
    $this->assertEquals("closed_by_seller", $return->status);
  }

}
