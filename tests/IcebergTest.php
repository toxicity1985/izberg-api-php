<?php
require_once "iceberg.php";

class IcebergTest extends PHPUnit_Framework_TestCase
{

    public function testCanBeNegated()
    {
        // Arrange
        $a = new Iceberg([
            "appNamespace" => "app_of_test",
            "apiKey" => "123234_api_key",
            "apiSecret" => "123234_api_secret",
            "email" => "myemail@example.com",
            "firstName" => "myfirstName",
            "lastName" => "myLastName"
        ]);

        // Assert
        $this->assertEquals("app_of_test", $a->getAppNamespace());
        $this->assertEquals("123234_api_key", $a->getApiKey());
        $this->assertEquals("123234_api_secret", $a->getApiSecret());
        $this->assertEquals("myemail@example.com", $a->getEmail());
        $this->assertEquals("myfirstName", $a->getFirstName());
        $this->assertEquals("myLastName", $a->getLastName());
        $this->assertEquals(Iceberg::DEFAULT_CURRENCY, $a->getCurrency());
        $this->assertEquals(Iceberg::DEFAULT_SHIPPING_COUNTRY, $a->getShippingCountry());
    }

}
