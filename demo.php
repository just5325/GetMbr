<?php

require_once './vendor/autoload.php';

use Hcg\GetMbr\GetMbr as GetMbr;

$gpsdis = (new Getmbr)->Main(104.031252,30.710894,10);
$distance = (new Getmbr)->Distance(104.031252, 30.710894, 103.863918,30.447486);

var_dump($gpsdis);
var_dump($distance);