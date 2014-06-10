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
            "apiSecret" => "123234_api_secret"
        ]);

        // Assert
        $this->assertEquals("app_of_test", $a->getAppNamespace());
        $this->assertEquals("123234_api_key", $a->getApiKey());
        $this->assertEquals("123234_api_secret", $a->getApiSecret());
    }

}
