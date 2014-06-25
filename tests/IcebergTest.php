<?php
require_once "Iceberg.php";

class IcebergTest extends PHPUnit_Framework_TestCase
{
    // ======================================
    //            TESTS FUNCTIONS
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
         $a = $this->getIceberg(array(
             "appNamespace" => "sebfie",
             "apiKey" => "bd86055e-fccd-4686-a29c-db17cfdb22fa",
             "apiSecret" => "02aad76d-fd75-4626-9429-af5075259cd0",
             "email" => "sebastien.fieloux@gmail.com",
             "firstName" => "sébastien",
             "lastName" => "fieloux"
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

        return $stub;
    }



    public function getRealAnswer() {
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

    public function getErrorAnswer() {
        return array(
            "error" => array(
                "msg" => "An error happened"
            )
        );
    }

    // ======================================
    //                  TESTS
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
        $a->getProducts();
    }

    public function testGetFullProductImportShouldReturnAllProducts()
    {
        $a = $this->getRealIcebergInstance();
        $merchant_id = 511;

        $result = $a->getFullProductImport($merchant_id);
        $this->assertTrue(is_string ($result));

        $result = $a->getFullProductImport($merchant_id, true);
        $this->assertTrue(is_a($result, "SimpleXMLElement"));
    }


    public function testGetProductSchemaShouldReturnProductSchema()
    {
        $a = $this->getRealIcebergInstance();
        $a->getProductsSchema();
    }

    public function testGetCategoriesShouldReturnCategories()
    {
        $a = $this->getRealIcebergInstance();
        $a->getCategories();
    }

    public function testGetMerchantsShouldReturnMerchants()
    {
        $a = $this->getRealIcebergInstance();
        $a->getMerchants();
    }

    public function testGetMerchantsSchemaShouldReturnMerchantsSchema()
    {
        $a = $this->getRealIcebergInstance();
        $a->getMerchantsSchema();
    }



}
