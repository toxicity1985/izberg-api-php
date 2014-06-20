#Iceberg-API-PHP

## About

Wrapper PHP around the Iceberg API

## Get started

[Create an account](http://account.iceberg-marketplace.com) on Iceberg-Marketplace.
[Create an application](http://dashboard.iceberg-marketplace.com) to be able to use this api.


### Initialize the class

```php
<?php
    require_once 'iceberg.php';

    $iceberg = new Iceberg(array(
      'apiKey'      => 'YOUR_APP_KEY',
      'apiSecret'   => 'YOUR_APP_SECRET',
      'appNamespace' => 'YOUR_APP_NAMESPACE',
      "email" => "YOUR_EMAIL",
      "firstName" => "YOUR_ACCOUNT_FIRST_NAME",
      "lastName" => "YOUR_ACCOUNT_LAST_NAME"
    ));

?>
```

### Run tests

- Install php unit : http://phpunit.de/getting-started.html
- cd /to/the/iceberg/php/library/folder
- run 'phpunit tests/IcebergTest.php'



