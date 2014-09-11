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


## Order Process

### Simple Order

Creating an order on Iceberg is really easy, the only thing you need is the Item ID, and your customer's informations.

```php

<?php
	
	require_once "iceberg.php";

	$valid_array = array(
  		'appNamespace' => 'YOUR_APP_NAMESPACE',
  		'accessToken'      => 'YOUR_ACCESSTOKEN',
  		'username'   => 'YOUR_USERNAME',
  		'apiKey'      => 'YOUR_APP_KEY',
  		'apiSecret'   => 'YOUR_APP_SECRET'
	  )

	$IcebergInstance = new Iceberg($valid_array);

    $IcebergInstance->setUser(array(
            "email" => "EMAIL_ADDRESS",
            "first_name" => "FIRST_NAME",
            "last_name" => "LAST_NAME"
        ));

```

Now that we have set the User informations, we can add the offer to the cart.

```php

	$id_offer = "MY OFFER ID";
	$quantity = "MY OFFER QUANTITY";

	$my_cart = IcebergInstance->newCart();
	$IcebergInstance->addCartItem(array(
		'offer_id' => $id_offer,
		'quantity' => (int)$quantity,
		)

```

You have to use addCartItem() for each different offer you want to add to your cart.

We need the country_id in in order to set the customer's address (Default value is "FR").

```php

        $country = $IcebergInstance->getCountry(array("code" => "FR"));
```
Now we can set the Shipping and Billing addresses.

```php

        $address = $IcebergInstance->createAddresses(array(
            "address" => "ADDRESS LINE 1",
            "address2" => "ADDRESS LINE 2",
            "city" => "CITY NAME
            "company" => "OPTIONNAL COMPANY NAME",
            "country" => "/v1/country/" . $country->id . "/",
            "default_billing" => true,
            "default_shipping" => true,
            "digicode" => null,
            "first_name" => "FIRST NAME",
            "floor" => null,
            "last_name" => "LAST NAME",
            "name" => "ADDRESS NAME",
            "phone" => "PHONE NUMBER",
            "state" => "OPTIONNAL STATE NAME",
            "status" => 10, // 0 : INACTIVE // 10 : ACTIVE // 90 : HIDDEN
            "zipcode" => "ZIPCODE"
        ));

 		$IcebergInstance->setBillingAddress($address->id);
        $IcebergInstance->setShippingAddress($address->id);


```
Now that both addresses are set, we can place the order.

```php

        $order = $IcebergInstance->createOrder();
        $order->authorizeOrder();

?>

```

###Complete Order

Here is an arbitrary exemple of a complete order process.


We get the first merchant with getMerchants()

```php

<?php
	
	require_once "iceberg.php";

	$valid_array = array(
	  'appNamespace' => 'YOUR_APP_NAMESPACE',
      'accessToken'      => 'YOUR_ACCESSTOKEN',
      'username'   => 'YOUR_USERNAME',
      'apiKey'      => 'YOUR_APP_KEY',
      'apiSecret'   => 'YOUR_APP_SECRET'
	  )

	$IcebergInstance = new Iceberg($valid_array);
	$merchants = $IcebergInstance->getMerchants();
	$my_merchant = $merchants->object[0];

```
and we get his products using getFullProductImport(), then we get the best offer's id from the first product.

```php

	$products = $IcebergInstance->getFullProductImport($merchant->id);
	$product = $products->product;
	$best_offer_id = (string) $product->best_offer->id;

```
Now that we have an offer ID, the process is the same as above

```php

    $IcebergInstance->setUser(array(
            "email" => "EMAIL_ADDRESS",
            "first_name" => "FIRST_NAME",
            "last_name" => "LAST_NAME"
        ));

	$my_cart = IcebergInstance->newCart();
	$IcebergInstance->addCartItem(array(
		'offer_id' => $best_offer_id,
		'quantity' => 1
		)
    $country = $IcebergInstance->getCountry(array("code" => "FR"));
    $address = $IcebergInstance->createAddresses(array(
            "address" => "ADDRESS LINE 1",
            "address2" => "ADDRESS LINE 2",
            "city" => "CITY NAME
            "company" => "OPTIONNAL COMPANY NAME",
            "country" => "/v1/country/" . $country->id . "/",
            "default_billing" => true,
            "default_shipping" => true,
            "digicode" => null,
            "first_name" => "FIRST NAME",
            "floor" => null,
            "last_name" => "LAST NAME",
            "name" => "ADDRESS NAME",
            "phone" => "PHONE NUMBER",
            "state" => "OPTIONNAL STATE NAME",
			//STATUS | 0 : INACTIVE | 10 : ACTIVE | 90 : HIDDEN
            "status" => 10,
            "zipcode" => "ZIPCODE"
   	    	));

	$IcebergInstance->setBillingAddress($address->id);
    $IcebergInstance->setShippingAddress($address->id);
    
	$order = $IcebergInstance->createOrder();    
	$order->authorizeOrder();

?>

```



### Run tests

- Install php unit : http://phpunit.de/getting-started.html
- cd /to/the/iceberg/php/library/folder
- run 'phpunit tests/IcebergTest.php'



