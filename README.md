#Iceberg-API-PHP
===============

## About

Wrapper PHP around the Iceberg API

## Get started

Donc pour le moment, c'est lié à modizy.com et l'api est disponible avec http://api.modizy.com . Il faut que tu t'inscrives d'abord sur http://fr.modizy.com et après tu fais une demande pour être développeur sur http://developers.modizy.com/


[Create an account](http://fr.modizy.com) with Modizy.
[Request a developer access](http://developers.modizy.com/) and wait the support validation.
[Create an application](http://developers.modizy.com/) to be able to use this api.


### Initialize the class

```php
<?php
    require_once 'iceberg.php';

    $iceberg = new Iceberg(array(
      'apiKey'      => 'YOUR_APP_KEY',
      'apiSecret'   => 'YOUR_APP_SECRET',
      'appNamespace' => 'YOUR_APP_NAMESPACE'
    ));

?>
```

### Run tests

- Install php unit : http://phpunit.de/getting-started.html
- cd /to/the/iceberg/php/library/folder
- run 'phpunit tests/IcebergTest.php'
