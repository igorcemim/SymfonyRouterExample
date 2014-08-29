<?php

require 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", TRUE);

use Application\Core\Bootstrap;

$app = new Bootstrap();
$app->run();
