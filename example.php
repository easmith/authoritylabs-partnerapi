<?php

require_once("vendor/autoload.php");

$al = new AuthorityLabs('my_token');

print_r($al->accountInfo());