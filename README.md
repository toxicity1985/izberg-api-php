#Iceberg-API-PHP

## About

Wrapper PHP around the Iceberg API

## Get started

[Create an account](http://account.iceberg-marketplace.com) on Iceberg-Marketplace.
[Create an application](http://dashboard.iceberg-marketplace.com) to be able to use this api.


### Initialize the class

You can use your access token or our Single Sign On system :

#### With Access token :

```php
<?php
    require_once 'iceberg.php';

    $iceberg = new Iceberg(array(
      'appNamespace' => 'YOUR_APP_NAMESPACE',
      'accessToken'      => 'YOUR_ACCESSTOKEN',
      'username'   => 'YOUR_USERNAME',
      'apiKey'      => 'YOUR_APP_KEY',
      'apiSecret'   => 'YOUR_APP_SECRET'
    ));

?>
```

#### With SSO :

```php
<?php
    require_once 'iceberg.php';

    $iceberg = new Iceberg(array(
      'appNamespace' => 'YOUR_APP_NAMESPACE',
      'apiKey'      => 'YOUR_APP_KEY',
      'apiSecret'   => 'YOUR_APP_SECRET'
    ));

    $iceberg->sso(array(
      "email" => "YOUR_EMAIL",
      "firstName" => "YOUR_ACCOUNT_FIRST_NAME",
      "lastName" => "YOUR_ACCOUNT_LAST_NAME"
    ));

?>
```


#### Authentification

By default we authenticate the admin user with informations specified on initialization (email, firstname, lastname). If you want to authenticate a new user, you can do it using the setUser function :

```php
<?php

  $iceberg->setUser(array(
    "email" => "myemail@yahoo.fr",
    "first_name" => "seb",
    "last_name" => "fie"
  ));
?>
```

This is useful when you want to link your api calls to a user, you will need it during an order process.

### Run tests

- Install php unit : http://phpunit.de/getting-started.html
- cd /to/the/iceberg/php/library/folder
- run 'phpunit tests/IcebergTest.php'



