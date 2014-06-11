<?php

/**
 * Iceberg API class
 * API Documentation: http://developers.modizy.com/documentation/
 * Class Documentation: https://github.com/Modizy/Iceberg-API-PHP
 *
 * @author Sebastien FIELOUX
 * @since 30.10.2011
 * @copyright Modizy.com 2014
 * @version 2.0
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Iceberg {

  /**
   * The API base URL
   */
  const API_URL = 'https://api.modizy.com/v1/';

  /**
   * The Single Sign On URL
   */
  const SINGLE_SIGN_ON_URL = 'user/sso/';

  /**
   * The default currency
   */
  const DEFAULT_CURRENCY = 'EUR';

  /**
   * The Default shipping country
   */
  const DEFAULT_SHIPPING_COUNTRY = 'FR';


  /**
   * The iceberg application namespace
   *
   * @var string
   */
  private $_appnamespace;

  /**
   * The iceberg api secret
   *
   * @var string
   */
  private $_apisecret;

  /**
   * The iceberg api key
   *
   * @var string
   */
  private $_apikey;

  /**
   * The user email
   *
   * @var string
   */
  private $_email;

  /**
   * The user first name
   *
   * @var string
   */
  private $_first_name;

  /**
   * The user last name
   *
   * @var string
   */
  private $_last_name;

  /**
   * The user currency
   *
   * @var string
   */
  private $_currency;

  /**
   * The user shipping country
   *
   * @var string
   */
  private $_shipping_country;


  /**
   * Default constructor
   *
   * @param array|string $config          Iceberg configuration data
   * @return void
   */
  public function __construct($config) {
    if (true === is_array($config)) {
      // if you want to access user data
      $this->setApiKey($config['apiKey']);
      $this->setApiSecret($config['apiSecret']);
      $this->setAppNamespace($config['appNamespace']);
      $this->setEmail($config['email']);
      $this->setFirstName($config['firstName']);
      $this->setLastName($config['lastName']);
      (isset($config['currency'])) ? $this->setCurrency($config['currency']) : $this->setCurrency(self::DEFAULT_CURRENCY);
      (isset($config['shippingCountry'])) ? $this->setShippingCountry($config['shippingCountry']) : $this->setShippingCountry(self::DEFAULT_SHIPPING_COUNTRY);
    } else {
      throw new Exception("Error: __construct() - Configuration data is missing.");
    }
  }


  /**
   * API-key Getter
   *
   * @return String
   */
  public function getApiKey() {
    return $this->_apikey;
  }

  /**
   * API-secret Getter
   *
   * @return String
   */
  public function getApiSecret() {
    return $this->_apisecret;
  }

  /**
   * NAMESPACE Getter
   *
   * @return String
   */
  public function getAppNamespace() {
    return $this->_appnamespace;
  }

  /**
   * Email Getter
   *
   * @return String
   */
  public function getEmail() {
    return $this->_email;
  }

  /**
   * First name Getter
   *
   * @return String
   */
  public function getFirstName() {
    return $this->_first_name;
  }

  /**
   * Last name Getter
   *
   * @return String
   */
  public function getLastName() {
    return $this->_last_name;
  }

  /**
   * Currency Getter
   *
   * @return String
   */
  public function getCurrency() {
    return $this->_currency;
  }

  /**
   * Shipping Country Getter
   *
   * @return String
   */
  public function getShippingCountry() {
    return $this->_shipping_country;
  }


  public function getMessageAuth() {
    $email = "test@modizy.com";
    $first_name = "Bill";
    $last_name = "Murray";
    $timestamp = time();
    $secret_key = "123";
    $to_compose = array($email, $first_name, $last_name, $timestamp);
    $message_auth = hash_hmac('sha1', implode(";", $to_compose), $secret_key);
    echo $message_auth;
  }


  /**
   * API-key Setter
   *
   * @param string $apiKey
   * @return void
   */
  public function setApiKey($apiKey) {
    $this->_apikey = $apiKey;
  }

  /**
   * API-secret Setter
   *
   * @param string $apiSecret
   * @return void
   */
  public function setApiSecret($apiSecret) {
    $this->_apisecret = $apiSecret;
  }

  /**
   * NAMESPACE Setter
   *
   * @param string $namespace
   * @return void
   */
  public function setAppNamespace($namespace) {
    $this->_appnamespace = $namespace;
  }

  /**
   * Email Setter
   *
   * @param string $email
   * @return void
   */
  public function setEmail($email) {
    $this->_email = $email;
  }

  /**
   * First name Setter
   *
   * @param string $firstname
   * @return void
   */
  public function setFirstName($firstname) {
    $this->_first_name = $firstname;
  }

  /**
   * Last name Setter
   *
   * @param string $lastname
   * @return void
   */
  public function setLastName($lastname) {
    $this->_last_name = $lastname;
  }

  /**
   * Currency Setter
   *
   * @param string $currency
   * @return void
   */
  public function setCurrency($currency) {
    $this->_currency = $currency;
  }

  /**
   * Shipping country Setter
   *
   * @param string $shippingCountry
   * @return void
   */
  public function setShippingCountry($shippingCountry) {
    $this->_shipping_country = $shippingCountry;
  }

  /**
   * The call operator
   *
   * @param string $function              API resource path
   * @param array [optional] $params      Additional request parameters
   * @param boolean [optional] $auth      Whether the function requires an access token
   * @param string [optional] $method     Request type GET|POST
   * @return mixed
   */
  protected function _makeCall($path, $method = 'GET', $params = null) {
    if (isset($params) && is_array($params)) {
      $paramString = '&' . http_build_query($params);
    } else {
      $paramString = null;
    }

    $apiCall = self::API_URL . $path . (('GET' === $method) ? $paramString : null);

    $headers = array(
      'Content-type: application/json',
      'Authorization: '. $this->getMessageAuth()
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if ('POST' === $method) {
      curl_setopt($ch, CURLOPT_POST, count($params));
      curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
    } else if ('DELETE' === $method) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $jsonData = curl_exec($ch);
    if (false === $jsonData) {
      throw new Exception("Error: _makeCall() - cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($jsonData);
  }


}