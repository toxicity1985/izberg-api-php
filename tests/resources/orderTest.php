<?php
class OrderTest extends BaseTester
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

	public function testFullOrderProcess()
	{
		\VCR\VCR::insertCassette('testFullOrderProcess');

    ini_set("memory_limit","1024M");
		$a = $this->getIzberg();
		// We get the first merchant
		$merchants = $a->get_list('merchant');
		$merchant = $merchants[0];
		$products = $merchant->getCatalog();
		$product = $products->product;
		$best_offer_id = (string) $product->best_offer->id;
		$i = 0;
		while ((int)$product->best_offer->variations->variation[$i]->stock === 0)
		{
			$i++;
		}
		$best_variation = (string) $product->best_offer->variations->variation[$i]->id;
		$my_cart = $a->get('Cart');
		$my_cart->addItem(array(
			"offer_id" => $best_offer_id,
			"variation_id" => $best_variation,
			"quantity" => 1
			));
		$country = $a->get('country');
		$address = $a->create('address', array(
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
		$my_cart->setBillingAddress($address->id);
		$my_cart->setShippingAddress($address->id);
		$order = $my_cart->createOrder();
	  // Place the order
		$order->updateStatus('authorizeOrder');
		$this->assertEquals("60", $order->status);
	}
}
