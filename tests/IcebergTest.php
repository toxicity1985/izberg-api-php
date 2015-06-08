<?php

require_once "lib/iceberg.php";

class IcebergTest extends PHPUnit_Framework_TestCase
{
	// ======================================
	// TESTS FUNCTIONS
	// ======================================


	public function getDefaultOptions()
	{
		return array(
			"appNamespace" => "app_of_test",
			"apiKey" => "123234_api_key",
			"apiSecret" => "123234_api_secret",
			"email" => "myemail@example.com",
			"firstName" => "myfirstName",
			"lastName" => "myLastName"
		);
	}


	public function getRealIcebergInstance()
	{
		$a = $this->getRealIcebergInstanceWithToken();
		return $a;
	}


	public function getRealIcebergInstanceWithToken()
	{
		$a = $this->getIceberg(array(
				"username" => getenv("USERNAME1"),
				"accessToken" => getenv("TOKEN1"),
				"sandbox" => true
		));
		return $a;
	}

	public function getIceberg($options = array())
	{
		if (empty($options)) {
			$options = $this->getDefaultOptions();
		}
		$a = new Iceberg($options);
		return $a;
	}

	public function mockSuccessSingleSignOnResponse($options = array())
	{
		return $this->mockIceberg($options, true);
	}

	public function mockErrorSingleSignOnResponse($options = array())
	{
		return $this->mockIceberg($options, false);
	}

	public function mockIceberg($options= array(), $success = true)
	{
		if (empty($options)) {
			$options = $this->getDefaultOptions();
		}
		$methods = ($success) ? array('_getSingleSignOnResponse') : array('curlGetInfo', 'curlExec');
		$stub = $this->getMockBuilder('Iceberg')
			->setMethods($methods)
			->disableOriginalConstructor()
			->getMock();
		$array = $success ? $this->getRealAnswer() : $this->getErrorAnswer();
		$response = (object) $array;
		if ($success) {
			$stub->expects($this->any())
				->method('_getSingleSignOnResponse')
				->will($this->returnValue($response));
		} else {
			$stub->expects($this->any())
				->method('curlGetInfo')
				->will($this->returnValue(300));
			$stub->expects($this->any())
				->method('curlExec')
				->will($this->returnValue(json_encode("[error: 'mymessage']")));
		}
		$stub->__construct($options);
		$stub->sso($options);
		return $stub;
	}

	public function getRealAnswer()
	{
		return array(
			"absolute_url" => "/user/sebfie/",
			"age" => "",
			"api_key" => "e0da0c1a729176449446a3cd606fd46e7a9a0c8a",
			"birth_date" => "" ,
			"city" => "",
			"country" => "",
			"created" => "",
			"dislikes_count" => "0",
			"display_name" => "sébastien fieloux",
			"email" => "sebastien.fieloux@gmail.com",
			"first_name" => "sébastien",
			"from_list" => "",
			"gender" => "F",
			"gid" => "u:73061",
			"groups" => array(),
			"id" => 73061,
			"is_application_staff" => 1,
			"is_staff" => "",
			"is_superuser" => "",
			"language" => "fr",
			"last_name" => "fieloux",
			"resource_uri" => "http://api.modizy.com/v1/user/73061/",
			"shopping_preference" => array(
				"country" => "",
				"currency" => "EUR",
				"feed_content" => "{}",
				"from_list" => "",
				"id" => "11674",
				"resource_uri" => "http://api.modizy.com/v1/user_shopping_prefs/11674/",
				"user" => "73061",
			),
			"timestamp" => "2014-06-11T16:18:57",
			"total_spent" => "0.00",
			"type" => "user",
			"username" => "sebfie"
		);
	}

	public function getErrorAnswer()
	{
		return array(
			"error" => array(
				"msg" => "An error happened"
			)
		);
	}


	// ======================================
	// TESTS
	// ======================================

	public function testConstructorUseParams()
	{
		$a = $this->mockSuccessSingleSignOnResponse();
		// // Assert
		$this->assertEquals("app_of_test", $a->getAppNamespace());
		$this->assertEquals("123234_api_key", $a->getApiKey());
		$this->assertEquals("123234_api_secret", $a->getApiSecret());
		$this->assertEquals("myemail@example.com", $a->getEmail());
		$this->assertEquals("myfirstName", $a->getFirstName());
		$this->assertEquals("myLastName", $a->getLastName());
		$this->assertEquals(Iceberg::DEFAULT_CURRENCY, $a->getCurrency());
		$this->assertEquals(Iceberg::DEFAULT_SHIPPING_COUNTRY, $a->getShippingCountry());
	}
	public function testSandboxParamIsWellUsedForUrlToRequest()
	{
		$a = new Iceberg(array("sandbox" => true, "appNamespace" => "lolote"));
		$this->assertEquals(PHPUnit_Framework_Assert::readAttribute($a, '_api_url'), "https://api.sandbox.iceberg.technology/v1/");
		$a = new Iceberg(array("appNamespace" => "lolote"));
		$this->assertEquals(PHPUnit_Framework_Assert::readAttribute($a, '_api_url'), "https://api.iceberg.technology/v1/");
	}

	public function testConstructorGetIcebergApiKey()
	{
		$a = $this->mockSuccessSingleSignOnResponse();
		$this->assertEquals("e0da0c1a729176449446a3cd606fd46e7a9a0c8a", $a->getIcebergApiKey());
	}

	public function testShouldThrowErrorIfNotGoodResponseCode()
	{
		$this->setExpectedException('GenericException', "Error: from Iceberg API - error: [error: 'mymessage']");
		$a = $this->mockErrorSingleSignOnResponse();
	}

	public function testGetInstanceShoudlReturnTheCreatedInstance()
	{
		$a = $this->mockSuccessSingleSignOnResponse();
		$this->assertEquals($a, Iceberg::getInstance());
	}

	public function testGetProductShouldReturnProducts()
	{
		$a = $this->getRealIcebergInstance();
		$products = $a->get_list("product");
		$this->assertTrue(is_array($products));
	}

	public function testGetFullProductImportShouldReturnAllProducts()
	{
		$b = $this->getRealIcebergInstance();
		$merchant = $b->get("merchant", 15);
		$result = $merchant->get_catalog();
	}

	public function testGetProductSchemaShouldReturnProductSchema()
	{
		$a = $this->getRealIcebergInstance();
		$product = $a->get_schema("product");
	}

	public function testGetCategoriesShouldReturnCategories()
	{
		$a = $this->getRealIcebergInstance();
		$Categories = $a->get_list("category");
		$this->assertTrue(is_array($Categories));
	}

	public function testGetMerchantsShouldReturnMerchants()
	{
		$a = $this->getRealIcebergInstance();
		$merchants = $a->get_list("merchant");
		$this->assertTrue(is_array($merchants));
	}

	public function testGetMerchantsSchemaShouldReturnMerchantsSchema()
	{
		$a = $this->getRealIcebergInstance();
		$merchantSchema = $a->get_schema("merchant");
		$this->assertTrue(is_a($merchantSchema, "stdClass"));
	}

	public function testgetCartShouldReturnACart()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->get("cart");
		$this->assertArrayHasKey("id", (array)$cart);
	}

	public function testgetCartItemsShouldReturnCartItems()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->get("cart");
		$items = $cart->getItems();
		$this->assertTrue(is_array($cart->items));
	}

	public function testinDebugModeCartShouldReturnDebugEqualToTrue()
	{
		$a = $this->getRealIcebergInstance();
		$a->setDebug(true);
		$cart = $a->create('cart');
		$this->assertTrue($cart->debug);
	}

	public function testAddCartItemShouldAddItem()
	{
		$a = $this->getRealIcebergInstance();

		$my_cart = $a->get('Cart');
		$number_items = count($my_cart->getItems());
		$my_cart->addItem(array(
			"offer_id" => 27254,
			"variation_id" => 60873,
			"quantity" => 1
		));
		$cart = $a->get('Cart');
		$items = $cart->getItems();
		$this->assertEquals(count($items), $number_items + 1);
		// We remove the item
		$firstItem = $items[0];
		$firstItem->delete();
		$items = $cart->getItems();
		$this->assertEquals(count($items), $number_items);
	}

	public function testNewCartItemShouldCreateANewCart()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->create('cart');
		$cart1 = $a->create('cart');
		$this->assertNotSame($cart1->id, $cart->id);
	}

	public function testgetAdressesShouldReturnAdresses()
	{
		$a = $this->getRealIcebergInstance();
		$addresses = $a->get_list("address");
		$this->assertTrue(is_array($addresses));
	}

	public function testgetCountryShouldReturnTheCountry()
	{
		$a = $this->getRealIcebergInstance();
		$country = $a->get("country");
		$this->assertEquals($country->code, 'FR');
	}

	public function testcreateAddressesShouldReturnACreatedAddress()
	{
		$a = $this->getRealIcebergInstance();
		$country = $a->get("country");
		$addr = $a->create("address", array(
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
		$this->assertArrayHasKey("id", (array) $addr);
		// We check that this address is well linked to the user
		$addresses = $a->get_list("address");
		$this->assertTrue(is_array($addresses));
	}

	public function testGetAddressShouldReturnAddress()
	{
		$a = $this->getRealIcebergInstance();

		$country = $a->get("country");
		$addr = $a->create("address", array(
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
		// We check that this address is well linked to the user
		$new_address = $a->get("address", $addr->id);
		$this->assertEquals($new_address->id, $addr->id);
	}

	public function testSaveObject()
	{
		$a = $this->getRealIcebergInstance();
		$name = "random description ".uniqid();
		$addresses = $a->get_list('address');
		$address = $addresses[0];
		$address->name = $name;
		$address->save();
		$address_check = $a->get("address", $address->id);
		$this->assertEquals($address->name, $address_check->name);
	}

	// MAIN FUNCTION TO TEST THE FULL ORDER PROCESS

	// public function testFullOrderProcess()
	// {
	// 	ini_set("memory_limit","1024M");
	// 	$a = $this->getRealIcebergInstance();
	// 	// We get the first merchant
	// 	$merchants = $a->get_list('merchant');
	// 	$merchant = $merchants[0];
	// 	$products = $merchant->get_catalog();
	// 	$product = $products->product;
	// 	$best_offer_id = (string) $product->best_offer->id;
	// 	$i = 0;
	// 	while ((int)$product->best_offer->variations->variation[$i]->stock === 0)
	// 	{
	// 		$i++;
	// 	}
	// 	$best_variation = (string) $product->best_offer->variations->variation[$i]->id;
	// 	$my_cart = $a->get('Cart');
	// 	$my_cart->addItem(array(
	// 		"offer_id" => $best_offer_id,
	// 		"variation_id" => $best_variation,
	// 		"quantity" => 1
	// 		));
	// 	$country = $a->get('country');
	// 	$address = $a->create('address', array(
	// 		"address" => "Address line 1",
	// 		"address2" => "Address line 2",
	// 		"city" => "St remy de provence",
	// 		"company" => "Sebfie",
	// 		"country" => "/v1/country/" . $country->id . "/",
	// 		// "default_billing" => true,
	// 		// "default_shipping" => true,
	// 		"digicode" => null,
	// 		"first_name" => "sebastien",
	// 		"floor" => null,
	// 		"last_name" => "fieloux",
	// 		"name" => "House",
	// 		"phone" => "0698674532",
	// 		"state" => null,
	// 		"status" => 10,
	// 		"zipcode" => "13210"
	// 		));
	// 	$my_cart->setBillingAddress($address->id);
	// 	$my_cart->setShippingAddress($address->id);
	// 	$order = $my_cart->createOrder();
	//   // Place the order
	// 	$order->updateStatus('authorizeOrder');
	// 	$this->assertEquals("60", $order->status);
	// 	echo "Your order id is $order->id";
	// }
}
