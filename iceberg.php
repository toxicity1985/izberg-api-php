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
   * The user access token
   *
   * @var string
   */
  private $_accesstoken;


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
    } else {
      throw new Exception("Error: __construct() - Configuration data is missing.");
    }
  }



  /**
   * API-key Getter
   *
   * @param string $apiKey
   * @return String
   */
  public function getApiKey() {
    return $this->_apikey;
  }

  /**
   * API-secret Getter
   *
   * @param string $apiSecret
   * @return String
   */
  public function getApiSecret() {
    return $this->_apisecret;
  }

  /**
   * NAMESPACE Getter
   *
   * @param string $namespace
   * @return String
   */
  public function getAppNamespace() {
    return $this->_appnamespace;
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


}