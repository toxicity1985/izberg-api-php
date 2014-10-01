<?php

require_once "iceberg.php";

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
		//$a = $this->getIceberg(array(
			//"appNamespace" => "lolote",
			//"sandbox" => true
		//))->sso(array(
			//"apiKey" => getenv("APIKEY1"),
			//"apiSecret" => getenv("APISECRET1"),
			//"email" => "sebfie@yahoo.fr",
			//"firstName" => "sébastien",
			//"lastName" => "fieloux")
		//);
		 $a = $this->getRealIcebergInstanceWithToken();
		return $a;
	}


	public function getRealIcebergInstanceWithToken()
	{
		$a = $this->getIceberg(array(
			"appNamespace" => "lolote",
			"username" => "sebfie",
			"accessToken" => "156d219e38f84953c159a857738119bc0c35de96",
			"apiSecret" => "6cb0c550-9686-41af-9b5e-5cf2dc2aa3d0",
			"sandbox"=>true
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
		$this->assertEquals(PHPUnit_Framework_Assert::readAttribute($a, '_api_url'), "http://api.sandbox.iceberg.technology/v1/");
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
		$this->setExpectedException('Exception', "Error: from Iceberg API - error: [error: 'mymessage']");
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
		$product = $a->make("product");
		$product->get_list();
	}

	public function testGetFullProductImportShouldReturnAllProducts()
	{
		$a = $this->getRealIcebergInstance();
		// My little poesy
		$merchant = $a->make("merchant");
		$merchant_id = 14;
		$result = $merchant->get_catalog($merchant_id);
		$this->assertTrue(is_a($result, "SimpleXMLElement"));
		// It return false if we specify an unexisting merchant id
		$merchant_id = 511;
		$result = $merchant->get_catalog($merchant_id);
		$this->assertFalse($result);
	}

	public function testGetProductSchemaShouldReturnProductSchema()
	{
		$a = $this->getRealIcebergInstance();
		$product = $a->make("Product");
		$product->get_schema();
	}

	public function testGetCategoriesShouldReturnCategories()
	{
		$a = $this->getRealIcebergInstance();
		$Category = $a->make("Category");
		$Category->get_list();
	}

	public function testGetMerchantsShouldReturnMerchants()
	{
		$a = $this->getRealIcebergInstance();
		$merchant = $a->make("merchant");
		$merchants = $merchant->get_list();
		$this->assertTrue(is_a($merchants, "stdClass"));
		$this->assertTrue(is_array($merchants->objects));
	}

	public function testGetMerchantsSchemaShouldReturnMerchantsSchema()
	{
		$a = $this->getRealIcebergInstance();
		$merchant = $a->make("merchant");
		$merchantSchema =  $merchant->get_schema();
		$this->assertTrue(is_a($merchantSchema, "stdClass"));
	}

	public function testgetUserShouldReturnTheCurrentUser()
	{
		$a = $this->getRealIcebergInstance();
		$user = $a->getUser();
		$this->assertArrayHasKey("id", (array)$user);
	}

	public function testgetUserShouldReturnTheLastAuthenticatedUser()
	{
		$a = $this->getRealIcebergInstance();
		$user = $a->getUser();
		$this->assertEquals("sebfie@yahoo.fr", $user->email);
		// We set a new user
		$a->setUser(array(
			"email" => "sebastien.fieloux@gmail.com",
			"first_name" => "seb",
			"last_name" => "fie"
		));
		$user = $a->getUser();
		$this->assertEquals($user->email, "sebastien.fieloux@gmail.com");
	}

	public function testgetCartShouldReturnACart()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->make("cart");
		$firstId =$cart->getCurrent()->id;
		$this->assertArrayHasKey("id", (array)$cart);
	}

	public function testgetCartItemsShouldReturnCartItems()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->make("cart");
		$cur_cart = $cart->create();
		$items = $cart->getItems();
		$this->assertTrue(is_a($items, "stdClass"));
		$this->assertEquals($items->meta->total_count, 0);
		$this->assertTrue(is_array($items->objects));
	}

	public function testinDebugModeCartShouldReturnDebugEqualToTrue()
	{
		$a = $this->getRealIcebergInstance();
		$a->setDebug(true);
		$cart = $a->make('cart');
		$cart->setCurrent($cart->create());
		$this->assertTrue($cart->getCurrent()->debug);
	}

	public function testAddCartItemShouldAddItem()
	{
		/*
		$a = $this->getRealIcebergInstance();
		$a->newCart();
		$a->addCartItem(array(
			"offer_id" => 149,
			"variation_id" => 283,
			"quantity" => 2
		));
		$cart = $a->getCart();
		$items = $a->getCartItems();
		$this->assertEquals($items->meta->total_count, 1);
		// We remove the item
		$firstItem = $items->objects[0];
		$a->removeCartItem($firstItem->id);
		$items = $a->getCartItems();
		$this->assertEquals($items->meta->total_count, 0);
		 */
		return ;
	}

	public function testNewCartItemShouldCreateANewCart()
	{
		$a = $this->getRealIcebergInstance();
		$cart = $a->make('cart');
		$cart1 = $cart->create();
		$cart2 = $cart->create();
		$this->assertNotSame($cart1->id, $cart2->id);
	}

	// public function testgetAvailableCreditBalanceShouldReturnAFloat()
	// {
	// $a = $this->getRealIcebergInstance();
	// $balance = $a->getAvailableCreditBalance();
	// $this->assertEquals(0.0, $balance);
	// }
	public function testgetAdressesShouldReturnAdresses()
	{
		$a = $this->getRealIcebergInstance();
		$address = $a->make("address");
		$adresses = $address->get_list();
		$this->assertTrue($adresses->meta->total_count >= 0);
	}

	public function testgetCountryShouldReturnTheCountry()
	{
		$a = $this->getRealIcebergInstance();
		$country = $a->make("country");
		$france = $country->get(array("code" => "FR"));
		$this->assertEquals($france->code, 'FR');
	}

	public function testcreateAddressesShouldReturnACreatedAddress()
	{
		$a = $this->getRealIcebergInstance();
		$country = $a->make("country");
		$country->get();
		$addr = $a->make("address");
		$address = $addr->create(array(
			"address" => "Address line 1",
			"address2" => "Address line 2",
			"city" => "St remy de provence",
			"company" => "Sebfie",
			"country" => "/v1/country/" . $country->getCurrent()->id . "/",
			"default_billing" => true,
			"default_shipping" => true,
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
		$this->assertArrayHasKey("id", (array) $address);
		// We check that this address is well linked to the user
		$adresses = $addr->get_list();
		$this->assertNotEquals($adresses->meta->total_count, 0);
	}

	public function testGetAddressShouldReturnAddress()
	{
		$a = $this->getRealIcebergInstance();
		$country = $a->make("country");
		$cur_country = $country->get(array("code" => "FR"));
		$addr = $a->make("address");
		$address = $addr->create(array(
			"address" => "Address line 1",
			"address2" => "Address line 2",
			"city" => "St remy de provence",
			"company" => "Sebfie",
			"country" => "/v1/country/" . $cur_country->id . "/",
			"default_billing" => true,
			"default_shipping" => true,
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
		$new_address = $addr->get($address->id);
		$this->assertEquals($new_address->id, $address->id);
	}

	public function testSaveObject()
	{
		$b = $this->getIceberg(array(
								"username" => getenv("USERNAME1"),
								"accessToken" => getenv("TOKEN1"),
								"sandbox" => true
								));
		$description = "random description ".rand(0, 1000);
		$merchant = $b->make("merchant", 15);
		$my_merchant = $merchant->getCurrent();
		$my_merchant->description = $description;
		$test = $merchant->save($my_merchant);
		$merchant_check = $merchant->get(15);
		$this->assertEquals($my_merchant, $merchant_check);
	}

	// MAIN FUNCTION TO TEST THE FULL ORDER PROCESS
/*
	public function testFullOrderProcess()
	{
		ini_set("memory_limit","1024M");
		$a = $this->getRealIcebergInstance();
	// We get the first merchant
		$merchants = $a->getMerchants();
		$merchant = $merchants->objects[0];
		$products = $a->getFullProductImport($merchant->id);
		$product = $products->product;
		$best_offer_id = (string) $product->best_offer->id;
		$i = 0;
		while ((int)$product->best_offer->variations->variation[$i]->stock === 0)
		{
			echo"\nCURRENT VAR : " . $product->best_offer->variations->variation[$i]->id;
			$i++;
		}
		$best_variation = (string) $product->best_offer->variations->variation[$i]->id;
		echo "\nVARIATION STOCK" . $product->best_offer->variations->variation[$i]->stock;
		echo "\nBEST OFFER VARIATION :" . $best_variation;
		$a->setUser(array(
			"email" => "support@lolote.fr",
			"first_name" => "lolote",
			"last_name" => "lolita"
			));
	// We create a new cart
		$a->newCart();
		$a->addCartItem(array(
			"offer_id" => $best_offer_id,
			"variation_id" => $best_variation,
			"quantity" => 1
			));
		$country = $a->getCountry(array("code" => "FR"));
		$address = $a->createAddresses(array(
			"address" => "Address line 1",
			"address2" => "Address line 2",
			"city" => "St remy de provence",
			"company" => "Sebfie",
			"country" => "/v1/country/" . $country->id . "/",
			"default_billing" => true,
			"default_shipping" => true,
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
		$a->setBillingAddress($address->id);
		$a->setShippingAddress($address->id);
		$order = $a->createOrder(array(
			//"payment_info_id" => 10
			));
	// Place the order
		$a->authorizeOrder();
	}
 */
}
