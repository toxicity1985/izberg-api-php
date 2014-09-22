#!/usr/bin/env php
<?php

require_once "iceberg.php";

$Icbrg = new Iceberg(array("username"=>"mravenel", "accessToken"=>"e405da7aa2751d695ea420987e5af8759fb9e6ee", "sandbox"=>"true"));
$Merchant = new Merchant();
echo var_export($Merchant->get(15), true);
