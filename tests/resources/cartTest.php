<?php
class cartTest extends BaseTester
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
  public function testgetCartShouldReturnACart()
  {
    \VCR\VCR::insertCassette('testgetCartShouldReturnACart');

    $a = $this->getIzberg();
    $cart = $a->get("cart");
    $this->assertArrayHasKey("id", (array)$cart);
  }

  public function testgetCartItemsShouldReturnCartItems()
  {
    \VCR\VCR::insertCassette('testgetCartItemsShouldReturnCartItems');

    $a = $this->getIzberg();
    $cart = $a->get("cart");
    $items = $cart->getItems();
    $this->assertTrue(is_array($cart->items));
  }

  public function testinDebugModeCartShouldReturnDebugEqualToTrue()
  {
    \VCR\VCR::insertCassette('testinDebugModeCartShouldReturnDebugEqualToTrue');

    $a = $this->getIzberg();
    $a->setDebug(true);
    $cart = $a->create('cart');
    $this->assertTrue($cart->debug);
  }

  public function testAddCartItemShouldAddItem()
  {
    \VCR\VCR::insertCassette('testAddCartItemShouldAddItem');

    $a = $this->getIzberg();

    $my_cart = $a->get('Cart');
    $number_items = count($my_cart->getItems());
    $this->assertTrue($number_items==0);
    $my_cart->addItem(array(
      "offer_id" => 27254,
      "variation_id" => 60873,
      "quantity" => 1
    ));
    \VCR\VCR::eject();

    \VCR\VCR::insertCassette('testAddCartItemShouldAddItem2');
    $cart = $a->get('Cart');
    $items = $cart->getItems();
    $this->assertEquals(count($items), $number_items + 1);
    // We remove the item
    // TODO Uncomment this once the bug is fixed in staging
    $firstItem = $items[0];
    $firstItem->delete();
    \VCR\VCR::eject();

    \VCR\VCR::insertCassette('testAddCartItemShouldAddItem3');
    $items = $cart->getItems();
    $this->assertEquals(count($items), $number_items);
  }

  public function testNewCartItemShouldCreateANewCart()
  {
    \VCR\VCR::insertCassette('testNewCartItemShouldCreateANewCart1');
    $a = $this->getIzberg();
    $cart = $a->create('cart');
    \VCR\VCR::eject();

    \VCR\VCR::insertCassette('testNewCartItemShouldCreateANewCart2');
    $cart1 = $a->create('cart');
    $this->assertNotSame($cart1->id, $cart->id);
    \VCR\VCR::eject();
  }
}
