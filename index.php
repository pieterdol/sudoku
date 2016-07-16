<?php
require_once 'vendor/autoload.php';
require_once 'include/globals.php';

use Controllers\Http\Router;

$router = new Router();

$data = $router->handleRequests();
exit($data);