<?php
require_once "iceberg.php";

class IcebergTest extends PHPUnit_Framework_TestCase
{
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

    public function getIceberg($options = array())
    {
        if (empty($options)) {
          $options = $this->getDefaultOptions();
        }
        $a = new Iceberg($options);
        return $a;
    }

    public function mockGetSingleSignOnResponse()
    {
        $stub = $this->getMock('Iceberg', array("_getSingleSignOnResponse"), array($this->getDefaultOptions()));

        $array = array(
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

        $response = (object) $array;

        // Configure the stub.
        $stub->expects($this->any())
             ->method('_getSingleSignOnResponse')
             ->will($this->returnValue($response));

        return $stub;
    }

    public function testConstructorUseParams()
    {
        $a = $this->mockGetSingleSignOnResponse();

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
        $a = $this->getIceberg(array(
            "appNamespace" => "sebfie",
            "apiKey" => "bd86055e-fccd-4686-a29c-db17cfdb22fa",
            "apiSecret" => "02aad76d-fd75-4626-9429-af5075259cd0",
            "email" => "sebastien.fieloux@gmail.com",
            "firstName" => "sébastien",
            "lastName" => "fieloux"
        ));
        $this->assertEquals("e0da0c1a729176449446a3cd606fd46e7a9a0c8a", $a->getIcebergApiKey());
    }


}
