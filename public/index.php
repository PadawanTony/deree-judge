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
$router->get('/test', 'MainController', 'test');
$router->get('/carousel', 'MainController', 'carousel');
$router->get('/professor', 'MainController', 'professor');
$router->get('/login', 'MainController', 'login');
$router->get('/logout', 'MainController', 'logout');
$router->get('/selectProfessor', 'MainController', 'selectProfessor');


/******** POST ********/
//Public
$router->post('/login', 'MainController', 'postlogin');
$router->post('/selectProfessor', 'MainController', 'postSelectProfessor');
$router->post('/judge', 'MainController', 'postJudge');



//See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

