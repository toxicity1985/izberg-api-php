<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;

class ReturnTest extends BaseTester
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
  public function testCreateAReturn()
  {
    VCR::insertCassette('testCreateAReturn');

    $a = $this->getIzberg();
    $user = $a->get("user");

    // We get an existing order
    $merchant_order = $a->get("merchantOrder", 26390);
    $item = $merchant_order->items[0];

    // We return this item
    $merchant_order = $merchant_order->createReturn(array(
      "order_items" => array($item->id)
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

  public function testCreateARefund()
  {
    VCR::insertCassette('testCreateARefund');

    $a = $this->getIzberg();
    $user = $a->get("user");

    // We get an existing order
    $merchant_order = $a->get("merchantOrder", 26388);
    $item = $merchant_order->items[0];

    // We return this item
    $merchant_order = $merchant_order->createRefund(array(
      "order_items" => array($item->id)
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
