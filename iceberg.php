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
   * The singleton of Iceberg instance
   *
   * @var Iceberg
   */
  protected static $_singleton;

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
   * The iceberg application api key
   *
   * @var string
   */
  private $_apikey;


  /**
   * The iceberg api key
   *
   * @var string
   */
  private $_iceberg_apikey;

  /**
   * The user email
   *
   * @var string
   */
  private $_email;

  /**
   * The request timestamp
   *
   * @var string
   */
  private $_timestamp;

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
   * The single sign on response
   *
   * @var array
   */
  private $_single_sign_on_response;


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

  /**
   * Iceberg API key Getter
   *
   * @return String
   */
  public function getIcebergApiKey() {
    return $this->_iceberg_apikey;
  }

  /**
   * Timestamp Getter
   *
   * @return String
   */
  public function getTimestamp() {
    return $this->_timestamp;
  }

  /**
   * Message Auth Getter
   *
   * @return String
   */
  public function getMessageAuth() {
    $this->setTimestamp(time());
    $to_compose = array($this->getEmail(), $this->getFirstName(), $this->getLastName(), $this->getTimestamp());
    $message_auth = hash_hmac('sha1', implode(";", $to_compose), $this->getApiSecret());
    return $message_auth;
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
   * Iceberg API key Setter
   *
   * @param string $api_key
   * @return String
   */
  public function setIcebergApiKey($api_key) {
    $this->_iceberg_apikey = $api_key;
  }

  /**
   * Timestamp Setter
   *
   * @param string $api_key
   * @return String
   */
  public function setTimestamp($timestamp) {
    $this->_timestamp = $timestamp;
  }


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

      // We get the iceberg api key using the Single Sign On API
      $this->_single_sign_on_response = $this->_getSingleSignOnResponse();
      $this->setIcebergApiKey($this->_single_sign_on_response->api_key);

      // We save this instance as singleton
      self::setInstance($this);

    } else {
      throw new Exception("Error: __construct() - Configuration data is missing.");
    }
  }

  /**
   * Static function to get the last validated Instance
   *
   * @return Iceberg
   */
  public static function getInstance()
  {
    if (self::$_singleton) {
      return self::$_singleton;
    } else {
      throw new Exception("You should create a first validated Iceberg instance");
    }
  }

  /**
   * Set the default instance to a specified instance.
   *
   * @param Iceberg $iceberg An object instance of type Iceberg,
   *   or a subclass.
   * @return void
   */
  public static function setInstance(Iceberg $iceberg)
  {
      self::$_singleton = $iceberg;
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
  protected function _makeCall($path, $method = 'GET', $params = null, $content_type = 'Content-type: application/json') {
    if (isset($params) && is_array($params)) {
      $paramString = '&' . http_build_query($params);
    } else {
      $paramString = null;
    }

    $apiCall = self::API_URL . $path . (('GET' === $method) ? $paramString : null);

    $headers = array(
      $content_type,
      'Authorization: '. $this->getMessageAuth()
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiCall);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
    // curl_setopt($ch, CURLOPT_PROXYPORT, 8888);

    if ('POST' === $method) {
      curl_setopt($ch, CURLOPT_POST, count($params));
      curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($paramString, '&'));
    } else if ('DELETE' === $method) {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $jsonData = $this->curlExec($ch);
    if (false === $jsonData) {
      throw new Exception("Error: _makeCall() - cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($jsonData);
  }

  /**
   * Api key Getter
   *
   * @return String
   */
  protected function _getSingleSignOnResponse() {
    $params = array(
      "email" => $this->getEmail(),
      "first_name" => $this->getFirstName(),
      "last_name" => $this->getLastName(),
      "message_auth" => $this->getMessageAuth(),
      "timestamp" => $this->getTimeStamp(),
      "application" => $this->getAppNamespace()
    );

    $apiCall = self::API_URL . self::SINGLE_SIGN_ON_URL . "?" . http_build_query($params);

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

    // curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
    // curl_setopt($ch, CURLOPT_PROXYPORT, 8888);

    $jsonData = $this->curlExec($ch);
    $httpcode = $this->curlGetInfo($ch, CURLINFO_HTTP_CODE);

    if (false === $jsonData) {
      throw new Exception("Error: _getSingleSignOnResponse() - cURL error: " . curl_error($ch));
    }
    curl_close($ch);

    $jsonResponse = json_decode($jsonData);
    // We display the error only if the HTTP code is different of 200..300
    if (preg_match("/2\d{2}/", $httpcode)  == 0) {
      throw new Exception("Error: from Iceberg API - error: " . print_r($jsonResponse,true));
    }
    return $jsonResponse;
  }

  // curl functions
  protected function curlExec($ch) {
    return curl_exec($ch);
  }

  protected function curlGetInfo($ch, $name) {
    return curl_getinfo($ch, $name);
  }

  // =============
  // API FUNCTIONS
  // =============

  /**
   * get Products of an iceberg account
   *
   * @param array $params
   * $params can contain this keys :
   *   offset: Integer => The offset of the request (for pagination)
   *   limit: Integer => The limit of the request
   * @return Array
   */
  public function getProducts($params = null)
  {
    return $this->_makeCall("product/", "GET", $params);
  }

   /**
   * get Products of an iceberg merchant
   *
   * @param string $merchant_id
   * @return String
   */
  public function getFullProductImport($merchant_id)
  {
    return $this->_makeCall("merchant/$merchant_id/download_export/", "GET", null, 'Content-type: application/xml');
  }

  /**
   * get Products schema
   *
   * @return Array
   */
  public function getProductsSchema()
  {
    return $this->_makeCall("product/schema/");
  }

  /**
   * get all categories of Iceberg catalog
   *
   * @return Array
   */
  public function getCategories()
  {
    return $this->_makeCall("category/tree/");
  }

}