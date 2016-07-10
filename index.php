<?php
require_once 'vendor/autoload.php';

use Controllers\Http\Router;

$router = new Router();

$data = $router->handleRequests();
exit($data);