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
	 * The API production URL
	 */
	const PRODUCTION_API_URL = 'https://api.iceberg.technology/v1/';

	/**
	 * The API sandbox URL
	 */
	const SANDBOX_API_URL = 'http://api.sandbox.iceberg.technology/v1/';

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
	 * The API base URL
	 */
	protected static $_api_url;

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
	 * The iceberg application access_token
	 *
	 * @var string
	 */
	private $_access_token;

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
	 * The username
	 *
	 * @var string
	 */
	private $_username;

	/**
	 * The request timestamp
	 *
	 * @var string
	 */
	private $_timestamp;

	/**
	 * Boolean to know if we have to use sso
	 *
	 * @var string
	 */
	private $_use_sso;

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
	 * The user current cart
	 *
	 * @var stdObject
	 */
	private $_current_cart;

	/**
	 * The current_order
	 *
	 * @var stdObject
	 */
	private $current_order;

	/**
	 * The current_user
	 *
	 * @var stdObject
	 */
	private $_current_user;

	/**
	 * Debug mode
	 *
	 * @var boolean
	 */
	private $_debug;


	/**
	 * Countries
	 *
	 * @var array
	 */
	private $_countries;

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
	 * Access token Getter
	 *
	 * @return String
	 */
	public function getAccessToken() {
		return $this->_access_token;
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
	 * Username Getter
	 *
	 * @return String
	 */
	public function getUsername() {
		return $this->_username;
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
	public function getMessageAuth($email, $first_name, $last_name)
	{
		$this->setTimestamp(time());
		$to_compose = array($email, $first_name, $last_name, $this->getTimestamp());
		if (is_null($this->getApiSecret())) {
			throw new exception("To use SSO you have to set the api_secret");
		}
		$message_auth = hash_hmac('sha1', implode(";", $to_compose), $this->getApiSecret());
		return $message_auth;
	}


	/**
	 * API-key Setter
	 *
	 * @param string $apiKey
	 * @return void
	 */
	public function setApiKey($apiKey)
	{
		$this->_apikey = $apiKey;
	}

	/**
	 * Username Getter
	 *
	 * @return String
	 */
	public function setUsername($username)
	{
		$this->_username = $username;
	}

	/**
	 * Access token Setter
	 *
	 * @return String
	 */
	public function setAccessToken($access_token)
	{
		$this->_access_token = $access_token;
	}

	/**
	 * API-secret Setter
	 *
	 * @param string $apiSecret
	 * @return void
	 */
	public function setApiSecret($apiSecret)
	{
		$this->_apisecret = $apiSecret;
	}

	/**
	 * NAMESPACE Setter
	 *
	 * @param string $namespace
	 * @return void
	 */
	public function setAppNamespace($namespace)
	{
		$this->_appnamespace = $namespace;
	}

	/**
	 * Email Setter
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
	}

	/**
	 * First name Setter
	 *
	 * @param string $firstname
	 * @return void
	 */
	public function setFirstName($firstname)
	{
		$this->_first_name = $firstname;
	}

	/**
	 * Last name Setter
	 *
	 * @param string $lastname
	 * @return void
	 */
	public function setLastName($lastname)
	{
		$this->_last_name = $lastname;
	}

	/**
	 * Currency Setter
	 *
	 * @param string $currency
	 * @return void
	 */
	public function setCurrency($currency)
	{
		$this->_currency = $currency;
	}

	/**
	 * Shipping country Setter
	 *
	 * @param string $shippingCountry
	 * @return void
	 */
	public function setShippingCountry($shippingCountry)
	{
		$this->_shipping_country = $shippingCountry;
	}

	/**
	 * Iceberg API key Setter
	 *
	 * @param string $api_key
	 * @return String
	 */
	public function setIcebergApiKey($api_key)
	{
		$this->_iceberg_apikey = $api_key;
	}

	/**
	 * Timestamp Setter
	 *
	 * @param string $timestamp
	 * @return String
	 */
	public function setTimestamp($timestamp)
	{
		$this->_timestamp = $timestamp;
	}

	/**
	 * Debug Setter
	 *
	 * @param string $debug
	 * @return String
	 */
	public function setDebug($debug)
	{
		$this->_debug = $debug;
	}


	/**
	 * Default constructor
	 *
	 * @param array|string $config          Iceberg configuration data
	 * @return void
	 */
	public function __construct($config)
	{
		$this->_use_sso = false;
		$this->_debug = false;

		if (true === is_array($config)) {
			self::$_api_url = (isset($config['sandbox']) && $config['sandbox'] === true) ? self::SANDBOX_API_URL : self::PRODUCTION_API_URL;

			if (isset($config['accessToken'])) {
				$this->setAccessToken($config['accessToken']);
				$this->setUsername($config['username']);
			}

			if (isset($config['apiKey'])) $this->setApiKey($config['apiKey']);
			if (isset($config['apiSecret'])) $this->setApiSecret($config['apiSecret']);

			$this->setAppNamespace($config['appNamespace']);
			(isset($config['currency'])) ? $this->setCurrency($config['currency']) : $this->setCurrency(self::DEFAULT_CURRENCY);
			(isset($config['shippingCountry'])) ? $this->setShippingCountry($config['shippingCountry']) : $this->setShippingCountry(self::DEFAULT_SHIPPING_COUNTRY);

			// We save this instance as singleton
			self::setInstance($this);

		} else {
			throw new Exception("Error: __construct() - Configuration data is missing.");
		}
	}

	public function sso($config)
	{
		// if you want to access user data
		$this->setApiKey($config['apiKey']);
		$this->setApiSecret($config['apiSecret']);
		if (isset($config['appNamespace'])) $this->setAppNamespace($config['appNamespace']);
		$this->setEmail($config['email']);
		$this->setFirstName($config['firstName']);
		$this->setLastName($config['lastName']);

		// We get the iceberg api key using the Single Sign On API
		$this->setUser(array(
			"email" => $config['email'],
			"first_name" => $config['firstName'],
			"last_name" => $config['lastName'],
		));
		return $this;
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
	 * Function to know if we use accessToken or SSO
	 *
	 * @return Boolean
	 */
	public function useSso()
	{
		return $this->_use_sso;
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
	protected function _makeCall($path, $method = 'GET', $params = null, $accept_type = 'Accept: application/json')
	{
		if (isset($params) && is_array($params)) {
			$paramString = '?' . http_build_query($params);
		} else {
			$paramString = null;
		}

		$apiCall = self::$_api_url . $path . (('GET' === $method) ? $paramString : null);

		if ($this->useSso()) {
			$headers = array(
				$accept_type,
				'Authorization: IcebergAccessToken '. $this->_single_sign_on_response->username . ":" . $this->_single_sign_on_response->api_key
			);
		} else {
			$headers = array(
				$accept_type,
				'Authorization: IcebergAccessToken '. $this->getUserName() . ":" . $this->getAccessToken()
			);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiCall);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
		// curl_setopt($ch, CURLOPT_PROXYPORT, 8888);
		// curl_setopt($ch,CURLOPT_USERAGENT,"ELB-HealthChecker/1.0");

		if ('POST' === $method) {
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim(ltrim($paramString, '&'), '?'));
		} else if ('DELETE' === $method) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		} else if ('PUT' === $method) {
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim(ltrim($paramString, '&'), '?'));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		}

		$data = $this->curlExec($ch);
		if (false === $data) {
			throw new Exception("Error: _makeCall() - cURL error: " . curl_error($ch));
		}
		curl_close($ch);

		return ($accept_type == 'Accept: application/json') ? json_decode($data) : (($accept_type == 'Accept: application/xml') ?  simplexml_load_string($data) : $data);
	}

	/**
	 * Api key Getter
	 *
	 * @return String
	 */
	protected function _getSingleSignOnResponse($params = null)
	{
		$this->_use_sso = true;

		if(is_null($params)) {
			$params = array(
				"email" => $this->getEmail(),
				"first_name" => $this->getFirstName(),
				"last_name" => $this->getLastName()
			);
		}

		$params["message_auth"] = $this->getMessageAuth($params["email"], $params["first_name"], $params["last_name"]);
		$params["application"] = $this->getAppNamespace();
		$params["timestamp"] = $this->getTimeStamp();

		$apiCall = self::$_api_url . self::SINGLE_SIGN_ON_URL . "?" . http_build_query($params);

		$headers = array(
			'Accept: application/json',
			'Authorization: '. $this->getMessageAuth($params["email"],$params["first_name"],$params["last_name"])
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiCall);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
		// curl_setopt($ch, CURLOPT_PROXYPORT, 8888);
		// curl_setopt($ch,CURLOPT_USERAGENT,"ELB-HealthChecker/1.0");

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
	protected function curlExec($ch)
	{
		return curl_exec($ch);
	}

	protected function curlGetInfo($ch, $name)
	{
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
	public function getProducts($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("product/", "GET", $params, $accept_type);
	}

	/**
	 * get Product of an iceberg account using its id
	 *
	 * @param string/integer $id
	 * @return Object
	 */
	public function getProduct($id, $params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("product/$id", "GET", $params, $accept_type);
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
	 * get Products of an iceberg merchant
	 *
	 * @param string $merchant_id
	 * @return String || SimpleXMLElement depending of the $to_simplexml_object parameter
	 */
	public function getFullProductImport($merchant_id, $params = null, $accept_type = 'Accept: application/xml')
	{
		return $this->_makeCall("merchant/$merchant_id/download_export/", 'GET', $params , $accept_type);
	}



	/**
	 * get all categories of Iceberg catalog
	 *
	 * @return Array
	 */
	public function getCategories($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("category/", 'GET', $params, $accept_type);
	}

	/**
	 * get Merchants of an iceberg account
	 *
	 * @param array $params
	 * $params can contain this keys :
	 *   offset: Integer => The offset of the request (for pagination)
	 *   limit: Integer => The limit of the request
	 * @return Array
	 */
	public function getMerchants($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("merchant/", "GET", $params, $accept_type = 'Accept: application/json');
	}

	/**
	 * get a specific Merchant from an iceberg account
	 *
	 * @id int $id
	 * @return Array
	 */
	public function getMerchantById($id = 0)
	{
		return $this->_makeCall("merchant/".$id."/", "GET");
	}

	/**
	 * get Merchants schema
	 *
	 * @return Array
	 */
	public function getMerchantsSchema($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("merchant/schema/", 'GET', $params, $accept_type);

	}

	/**
	 * get current user cart
	 *
	 * @return StdObject
	 */
	public function getCart($params = null, $accept_type = 'Accept: application/json')
	{
		if (!$this->_current_cart) {
			$this->_current_cart = $this->_makeCall("cart/mine/", 'GET', $params, $accept_type);
		}
		return $this->_current_cart;
	}

	public function newCart($params = null, $accept_type = 'Accept: application/json')
	{
		if ($this->_debug) $params["debug"] = true;
		return $this->_makeCall("cart/", 'POST', $params, $accept_type);
	}

	/**
	 * get current cart items
	 *
	 * @return Array
	 */
	public function getCartItems($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("cart/" . $this->getCart()->id . "/items/", 'GET', $params, $accept_type);
	}

	/**
	 * add an item to a cart
	 *
	 * @return Array
	 */
	public function addCartItem($params = null, $accept_type = 'Accept: application/json')
	{
		// Params:
		//   offer_id: Integer
		//   variation_id: Integer
		//   quantity: Integer
		//   gift: Boolean
		//   bundled: Boolean
		return $this->_makeCall("cart/" . $this->getCart()->id . "/items/", 'POST', $params, $accept_type);
	}

	/**
	 * update an item to a cart
	 *
	 * @return Array
	 */
	public function updateCartItem($id, $params = null, $accept_type = 'Accept: application/json')
	{
		// Params:
		//   offer_id: Integer
		//   variation_id: Integer
		//   quantity: Integer
		//   gift: Boolean
		//   bundled: Boolean
		return $this->_makeCall("cart_item/" . $id . "/", 'PUT', $params, $accept_type);
	}

	/**
	 * delete an item to a cart
	 *
	 * @return Array
	 */
	public function removeCartItem($cart_item_id, $params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("cart_item/" . $cart_item_id . "/", 'DELETE', $params, $accept_type);
	}


	/**
	 * get current user credit balance
	 *
	 * @return Float
	 */
	// We will fix it later
	// public function getAvailableCreditBalance($params = null, $accept_type = 'Accept: application/json')
	// {
	//   return floatval($this->_makeCall("cart/" . $this->getCart()->id . "/get_available_credit_balance/", 'GET', $params, $accept_type));
	// }


	/**
	 * create order from current cart
	 *
	 * @return StdObject
	 */
	public function createOrder($params = null, $accept_type = 'Accept: application/json')
	{
		// params:
		//   - credit_use: Decimal. Amount to be use from user credit balance
		//   - payment_info_id: Integer. Id of the payment card if pay with registered card
		//   - pre_auth_id: Integer. Id of the PreAuthorization object from the payment backend

		$this->current_order = $this->_makeCall("cart/" . $this->getCart()->id . "/createOrder/", 'POST', $params, $accept_type);
		// We also clear the cart
		$this->_current_cart = null;

		return $this->current_order;
	}

	/**
	 * confirm order
	 *
	 * @return StdObject
	 */
	public function authorizeOrder($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("order/" . $this->current_order->id . "/authorizeOrder/", 'POST', $params, $accept_type);
	}

	/**
	 * get current authenticated user
	 *
	 * @return StdObject
	 */

	public function getUser()
	{
		return $this->_makeCall("user/me/", 'GET');
	}

	/**
	 * Use this user for current connection
	 *
	 * @return null
	 */
	public function setUser($params)
	{
		$this->_single_sign_on_response = $this->_getSingleSignOnResponse($params);
		$this->current_user = $this->getUser();
		$this->setIcebergApiKey($this->_single_sign_on_response->api_key);
	}

	/**
	 * Get country from params
	 * @param array $params
	 *    code: FR
	 * @return StdObject
	 */
	public function getCountry($params = array("code" => "FR"), $accept_type = 'Accept: application/json')
	{
		if (!$this->_countries)
			$this->_countries = array();
		if (!isset($this->_countries[$params["code"]])) {
			$response = $this->_makeCall("country/", 'GET', $params, $accept_type);
			$result = $response->objects[0];
			$this->_countries[$params["code"]] = $result;
		} else {
			$result = $this->_countries[$params["code"]];
		}
		return $result;
	}


	/**
	 * Get current user's addresses
	 *
	 * @return StdObject
	 */
	public function getAddresses($params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("address/", 'GET', $params, $accept_type);
	}

	/**
	 * Set cart billing address
	 *
	 * @return StdObject
	 */
	public function setBillingAddress($id, $params = null, $accept_type = 'Accept: application/json')
	{
		$params["billing_address"] = "/v1/address/$id/" ;
		return $this->_makeCall("cart/" . $this->_current_cart->id . "/", 'PUT', $params, $accept_type);
	}

	/**
	 * Set cart shipping address
	 *
	 * @return StdObject
	 */
	public function setShippingAddress($id, $params = null, $accept_type = 'Accept: application/json')
	{
		$params["shipping_address"] = "/v1/address/$id/";
		return $this->_makeCall("cart/" . $this->_current_cart->id . "/", 'PUT', $params, $accept_type);
	}

	/**
	 * Create a user for the current user
	 *
	 * @return StdObject
	 */
	public function createAddresses($params = null, $accept_type = 'Accept: application/json')
	{
		// address: string
		// address2: string
		// city: string
		// company: string
		// country: string
		// default_billing: boolean
		// default_shipping: boolean
		// digicode: string
		// first_name: string
		// floor: string
		// last_name: string
		// name: string
		// phone: string
		// state: string
		// status:
		//   0: Inactive address
		//   10: Active address
		//   90: Hidden address
		// zipcode
		return $this->_makeCall("address/", 'POST', $params, $accept_type);
	}

	/**
	 * Get address from id
	 *
	 * @return StdObject
	 */
	public function getAddress($address_id, $params = null, $accept_type = 'Accept: application/json')
	{
		return $this->_makeCall("address/$address_id", 'GET', $params, $accept_type);
	}

	/**
	 * Get user payment informations
	 *
	 * @return StdObject
	 */
	public function getPaymentCardAlias($params = null, $accept_type = 'Accept: application/json')
	{
		$params["user"] = $this->current_user->id;
		return $this->_makeCall("/v1/payment_card_alias/", 'GET', $params, $accept_type);
	}

	//_makeCall($path, $method = 'GET', $params = null, $accept_type = 'Accept: application/json')
	public function get_object($name, $id = null, $params = null, $accept_type = "Accept: application/json")
	{
		return $this->_makeCall($name."/".$id, 'GET', $params, $accept_type);
	}

	public function create_object($name, $params = null)
	{
		return $this->_makeCall($name."/", 'POST', $params, $accept_type);
		return null;
	}

	public function update_object($name, $id)
	{
		return null;
	}

	public function delete_object($name, $id)
	{
		return null;
	}

	public function save_object($name, $id)
	{
		return null;
	}

	public function get_list($name, $id)
	{
		return null;
	}
