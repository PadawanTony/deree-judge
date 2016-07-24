<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/setup.php';

use Judge\Controllers;
use Judge\Router;

//Load .env variables
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../app/');
$dotenv->load();
$baseUrl = getenv('BASE_URL');

$router = new Router\Router();

/******** GET ********/
//Public
$router->get('/', 'MainController', 'index');


/******** POST ********/
//Public



//See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

