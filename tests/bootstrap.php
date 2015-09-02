<?php
require_once "base.php";
require_once __DIR__ . '/../vendor/autoload.php';

\VCR\VCR::turnOn();
# Turn VCR on before to load izberg if we want to catch curls
require_once "lib/izberg.php";
