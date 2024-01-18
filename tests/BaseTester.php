<?php

namespace Tests;

use Izberg\Izberg;
use PHPUnit\Framework\TestCase;

abstract class BaseTester extends TestCase
{
    public const APP_NAMESPACE = 'bebe_au_naturel';
    public const USERNAME = 'eric-et-guillaunetech';
    public const ACCESS_TOKEN = '618dc532427663869aee123450440f177db03f85';
    public const API_SECRET = 'f8089143-7198-4172-9744-09820e45e242';
    public const USER_EMAIL = 'myemail@yahoo.fr';
    public const USER_API_KEY = 'd43fce48-836c-43d3-9ddb-7da2e70af9f1';
    public const USER_API_SECRET = '6cb0c550-9686-41af-9b5e-5cf2dc2aa3d0';
    public const USER_FIRSTNAME = 'my_firstname';
    public const USER_LASTNAME = 'my_lastname';

    public function getShippingIzberg()
    {
        return $this->getIzberg(array(
            "appNamespace" => self::APP_NAMESPACE,
            "username" => self::USERNAME,
            "accessToken" => self::ACCESS_TOKEN,
            "apiSecret" => self::API_SECRET,
            "sandbox" => true,
        ));
    }

    public function getIzberg($options = [], $extra_mocks_methods = [])
    {
        if (empty($options)) {
            $options = [
                "appNamespace" => self::APP_NAMESPACE,
                "username" => self::USERNAME,
                "accessToken" => self::ACCESS_TOKEN,
                "apiSecret" => self::API_SECRET,
                "sandbox" => true,
            ];
        }
        $mock = $this->getMockBuilder(Izberg::class)
                     ->setMethods(array_merge(array('setTimestamp', 'getTimestamp', 'setNonce', 'getNonce', 'log'), $extra_mocks_methods))
                     ->setConstructorArgs(array($options))
                     ->getMock();

        $mock->expects($this->any())
             ->method('setTimestamp')
             ->will($this->returnValue(true));

        $mock->expects($this->any())
             ->method('getTimestamp')
             ->will($this->returnValue(1439912480));

        $mock->expects($this->any())
             ->method('setNonce')
             ->will($this->returnValue(true));

        $mock->expects($this->any())
             ->method('getNonce')
             ->will($this->returnValue(1439912482));

        return $mock;
    }

    public function sso(&$a)
    {
        $a->sso([
            "application" => self::APP_NAMESPACE,
            "email" => self::USER_EMAIL,
            "apiKey" => self::USER_API_KEY,
            "apiSecret" => self::USER_API_SECRET,
            "firstName" => self::USER_FIRSTNAME,
            "lastName" => self::USER_LASTNAME,
        ]);
    }

}
