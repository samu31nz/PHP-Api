<?php

include_once('app/_autoload.php');
include_once('app/App.php');

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

date_default_timezone_set('UTC');
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");

new App();
