<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

set_exception_handler(function ($e) {
    echo "EXCEPTION:\n";
    echo $e;
    exit(1);
});

set_error_handler(function ($severity, $message, $file, $line) {
    echo "ERROR:\n";
    echo $message . " in $file:$line";
    exit(1);
});

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
