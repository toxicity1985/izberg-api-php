<?php

namespace Tests\Resources;

use Tests\BaseTester;
use VCR\VCR;
use Izberg\Izberg;

class IzbergTest extends BaseTester
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

    public function testConstructorUseParams()
    {
        VCR::insertCassette('testConstructorUseParams');

        $a = $this->getIzberg();
        $this->sso($a);
        // Assertions
        $this->assertEquals("lolote", $a->getAppNamespace());
        $this->assertEquals("6cb0c550-9686-41af-9b5e-5cf2dc2aa3d0", $a->getApiSecret());
        $this->assertEquals("myemail@yahoo.fr", $a->getEmail());
        $this->assertEquals("my_firstname", $a->getFirstName());
        $this->assertEquals("my_lastname", $a->getLastName());
        $this->assertEquals(Izberg::DEFAULT_CURRENCY, $a->getCurrency());
        $this->assertEquals(Izberg::DEFAULT_SHIPPING_COUNTRY, $a->getShippingCountry());
    }

    public function testWeUseApiUrlInParams()
    {
        $a = new Izberg(array(
            "appNamespace" => "lolote",
            "username" => getenv("USERNAME1"),
            "accessToken" => getenv("TOKEN1"),
            "sandbox" => true,
            'apiUrl' => "http://www.myurl.com",
        ));
        $this->assertEquals($a->getApiUrl(), "http://www.myurl.com");
    }

    public function testWeUseLocaleInParams()
    {
        $a = new Izberg(array(
            "appNamespace" => "lolote",
            "username" => getenv("USERNAME1"),
            "accessToken" => getenv("TOKEN1"),
            "sandbox" => true,
            'locale' => "it",
        ));
        $this->assertEquals($a->getLocale(), "it");
    }

    public function testSandboxParamIsWellUsedForUrlToRequest()
    {
        $a = new Izberg(array("sandbox" => true, "appNamespace" => "lolote"));
        $this->assertEquals($a::getApiUrl(), "https://api.sandbox.iceberg.technology/v1/");
        $a = new Izberg(array("appNamespace" => "lolote"));
        $this->assertEquals($a::getApiUrl(), "https://api.iceberg.technology/v1/");
    }


    public function testShouldThrowErrorIfNotGoodResponseCode()
    {
        VCR::insertCassette('testShouldThrowErrorIfNotGoodResponseCode');

        $this->setExpectedException('Izberg\Exception\BadRequestException');
        $a = $this->getIzberg();
        $a->sso(array(
            "email" => "myemail@yahoo.fr",
            "apiKey" => "d43fce48-836c-43d3-9ddb-7da2e70af9f1",
            "apiSecret" => "6cb0c550-9686",
            "firstName" => "my_firstname",
            "lastName" => "my_lastname",
        ));
    }

    public function testGetInstanceShoudlReturnTheCreatedInstance()
    {
        VCR::insertCassette('testGetInstanceShoudlReturnTheCreatedInstance');

        $a = $this->getIzberg();
        $this->sso($a);

        $this->assertEquals($a, Izberg::getInstance());
    }

    public function testconvertHtmlShouldReturnTextWithoutHtml()
    {
        $text = "<p>My test</p>";
        $result = Izberg::convertHtml($text);

        $this->assertEquals($result, "My test");
    }

    public function testGetCurrentApp()
    {
        VCR::insertCassette('testGetCurrentApp');
        $a = $this->getIzberg();
        $application = $a->getCurrentApplication();
        $this->assertInstanceOf("Izberg\Resource\Application", $application);
        $this->assertEquals($application->namespace, "lolote");
    }

}
