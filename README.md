#Iceberg-API-PHP

## About

Wrapper PHP around the Iceberg API

## Get started

Donc pour le moment, c'est lié à modizy.com et l'api est disponible avec http://api.modizy.com . Il faut que tu t'inscrives d'abord sur http://fr.modizy.com et après tu fais une demande pour être développeur sur http://developers.modizy.com/


[Create an account](http://fr.modizy.com) with Modizy.
[Request a developer access](http://developers.modizy.com/) and wait the support validation.
[Create an application](http://developers.modizy.com/) to be able to use this api.


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

#### Sandbox

To use our sandbox environment, just pass the param `sandbox` in options when you create your iceberg object :

```php
<?php
    require_once 'iceberg.php';

    $iceberg = new Iceberg(array(
      'appNamespace' => 'YOUR_APP_NAMESPACE',
      'sandbox' => 'true'
    ));

?>
```

**Note** : It will request a none https url.

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
