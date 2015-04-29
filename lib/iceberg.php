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

require_once __DIR__."/../HtmlToText/HtmlToText.php";
require_once __DIR__."/resources/loader.php";


class Iceberg
{

	const LOGS = true;

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
	* The user shipping country
	*
	* @var string
	*/
	private $_shipping_country;

	/**
	* The user currency
	*
	* @var string
	*/
	private $_currency;

	/**
	* The single sign on response
	*
	* @var array
	*/
	private $_single_sign_on_response;

	/**
	* Debug mode
	*
	* @var boolean
	*/
	private $_debug;

	public function getDebug()
	{
		return $this->_debug;
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

	public static function getApiUrl()
	{
		return self::$_api_url;
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
	* get current authenticated user
	*
	* @return StdObject
	*/
	public function getUser()
	{
		return $this->get("user");
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
			self::$_api_url = (isset($config['apiUrl']))? $config['apiUrl']: self::$_api_url;

			if (isset($config['accessToken'])) {
				$this->setAccessToken($config['accessToken']);
				$this->setUsername($config['username']);
			}

			if (isset($config['apiKey']))
				$this->setApiKey($config['apiKey']);
			if (isset($config['apiSecret']))
				$this->setApiSecret($config['apiSecret']);
			if (isset($config['appNamespace']))
				$this->setAppNamespace($config['appNamespace']);
			(isset($config['currency'])) ? $this->setCurrency($config['currency']) : $this->setCurrency(self::DEFAULT_CURRENCY);
			(isset($config['shippingCountry'])) ? $this->setShippingCountry($config['shippingCountry']) : $this->setShippingCountry(self::DEFAULT_SHIPPING_COUNTRY);

			// We save this instance as singleton
			self::setInstance($this);
			Ice\Resource::setIceberg($this);

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
	* The Log Function
	*
	* @param string $Message               Your log message
	* @param string [optional]             Log type (default is "ERROR")
	* @param string [optional]             Directory path for logs, CWD by default
	**/
	public function log($message, $level="error", $path = null)
	{
		date_default_timezone_set("Europe/Paris");
		if (false === self::LOGS)
			return ;
		if (false === is_dir($path))
			$path = __DIR__."/../log/";
		else if (substr($path, -1) != '/')
			$path .= '/';

		file_put_contents($path."log-".$level."-".date("m-d").".txt", date("H:i:s | ").$message."\n", FILE_APPEND);
	}

	/**
	 * Get the inline user token
	 * @return String
	 */
	public function getInlineUserToken()
	{
		if ($this->useSso()) {
			if (strtolower($this->_single_sign_on_response->username) == 'anonymous'){
				$api_key = $this->_single_sign_on_response->api_key;
				if (!is_null($this->getApiKey()))
					$api_key = $this->getApiKey();
				$h = $this->_single_sign_on_response->username . ":" . $this->getAppNamespace() .":".$api_key;
			}
			else
				$h = $this->_single_sign_on_response->username . ":" .$this->_single_sign_on_response->api_key;
			$headers = 'IcebergAccessToken '. $h;
		} else {
			$headers = 'IcebergAccessToken '. $this->getUserName() . ":" . $this->getAccessToken();
		}

		return $headers;
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
	public function Call($path, $method = 'GET', $params = null, $accept_type = 'Accept: application/json', $staff = false)
	{
		if (isset($params) && is_array($params) && $accept_type == "Content-Type: application/json")
		{
			$paramString = json_encode($params);
		}
		else if (isset($params) && is_array($params)) {
			$paramString = '?' . http_build_query($params);
		} else {
			$paramString = null;
		}

		$apiCall = self::$_api_url . $path . (('GET' === $method) ? $paramString : null);

		if (true === $staff){

			$headers = array(
				$accept_type,
				'Authorization: IcebergAccessToken '. ICEBERG_USERNAME . ":" . ICEBERG_ACCESS_TOKEN,
			);
		}		
		elseif ($this->useSso()) {
			if (strtolower($this->_single_sign_on_response->username) == 'anonymous'){
				$api_key = $this->_single_sign_on_response->api_key;
				if (!is_null($this->getApiKey()))
					$api_key = $this->getApiKey();
				$h = $this->_single_sign_on_response->username . ":" . $this->getAppNamespace() .":".$api_key;
			}
			else
				$h = $this->_single_sign_on_response->username . ":" .$this->_single_sign_on_response->api_key;
			$headers = array(
				$accept_type,
				'Authorization: IcebergAccessToken '. $h
			);
		} else {
			$headers = array(
				$accept_type,
				'Authorization: IcebergAccessToken '. $this->getUserName() . ":" . $this->getAccessToken()
			);
		}

		$ch = curl_init();

		if ($this->getDebug()) {
			curl_setopt($ch, CURLOPT_VERBOSE, true);
		}
		curl_setopt($ch, CURLOPT_URL, $apiCall);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
		// curl_setopt($ch, CURLOPT_PROXYPORT, 8888);

		if ('POST' === $method)
		{
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

		$this->log($method." | ".$apiCall, "call");
		if (!is_null($paramString))
			$this->log("PARAMS | ".ltrim(ltrim($paramString, '&'), '?'), "call", __DIR__."/log");
		$this->log("DATE | ".$data, "call");

		if (false === $data) {
			throw new Exception("Error: Call() - cURL error: " . curl_error($ch));
		}
		curl_close($ch);
		return ($accept_type == 'Accept: application/json' || $accept_type == 'Content-Type: application/json') ? json_decode($data) : (($accept_type == 'Accept: application/xml') ?  simplexml_load_string($data) : $data);
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


	/**
	* Test if AcessToken is valid
	*
	* @returns object
	*
	**/
	public function testIcebergToken()
	{
		try
		{
			$result = $this->Call('user/me/');
		}
		catch (Exception $e)
		{
			$result = false;
		}
		if (isset($result->id) && $result->id == 0)
			$result = false;
		return ($result);
	}

	/**
	* Converts html string to simple string
	*
	* @returns string
	*
	**/
	public static function convertHtml($html)
	{
		$converter = new \HtmlToText\HtmlToText($html);
		return $converter->convert();
	}

	/**
	* Factory method, use it to build resources
	*
	* @returns object
	*
	**/
	public function get($resource, $id = null, $params = null, $accept_type = "Accept: application/json", $endpoint = null)
	{
		if (strtolower($resource) == "cart" && !$id)
			$id = "mine";
		if (strtolower($resource) == "user" && !$id)
			$id = "me";
		if (strtolower($resource) == "country" && !$params)
			$params = array("code" => "FR");
		if (strncmp("Ice\\", $resource, 4) != 0)
			$resource = "Ice\\".$resource;
		$object = new $resource();
		if (!$endpoint)
			$endpoint =  $object->getName();
		if ($id)
			$response = $this->Call($endpoint."/".$id."/", 'GET', $params, $accept_type);
		else
			$response = $this->Call($endpoint."/", 'GET', $params, $accept_type);
		
		$object->hydrate($response);
		return $object;
	}


	/**
	* Factory method, use it to build resources
	*
	* @returns object
	*
	**/
	public function get_list($resource, $params = null, $accept_type = "Accept: application/json")
	{
		if (strncmp("Ice\\", $resource, 4) != 0)
			$resource = "Ice\\".$resource;
		$handler = new $resource();
		$list = $this->Call($handler->getName()."/", 'GET', $params, $accept_type);
		$object_list = array();
		foreach ($list->objects as $object)
		{
			$obj = new $resource();
			$obj->hydrate($object);
			$object_list[] = $obj;
		}
		return $object_list;
	}

	/**
	* Factory method, use it to build resources
	*
	* @returns object
	*
	**/
	public function create($resource, $params = null, $accept_type = "Content-Type: application/json")
	{
		if (strncmp("Ice\\", $resource, 4) != 0)
			$resource = "Ice\\".$resource;
		if ($this->getDebug())
			$params['debug'] = 'true';
		$object = new $resource();
		$response = $this->Call($object->getName()."/", 'POST', $params, $accept_type);
		$object->hydrate($response);
		return $object;
	}

	/**
	* Updates Object
	*
	* @return Object
	*
	**/
	public function update($resource = null, $id = null, $params = null, $accept_type = "Content-Type: application/json")
	{
		if (!$id || !$resource)
			throw new Exception(__METHOD__." needs a valid ID and a valid Resource Name");
		if (strncmp("Ice\\", $resource, 4) != 0)
			$resource = "Ice\\".$resource;
		$obj = new $resource();
		$name = $obj->getName();
		$response = $this->Call($name . "/" . $id . "/", 'PUT', $params, $accept_type);
		$obj->hydrate($response);
		return $obj;
	}

	/**
	* Get Schema
	*
	* @return Object
	*
	**/
	public function get_schema($resource, $params = null, $accept_type = 'Accept: application/json')
	{
		return $this->Call($resource."/schema", 'GET', $params, $accept_type);
	}
}
