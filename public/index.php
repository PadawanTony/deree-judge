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
$router->get('/carousel', 'MainController', 'carousel');
$router->get('/professor/[-\w\d\?\!\.]+', 'MainController', 'professor');
$router->get('/login', 'MainController', 'login');
$router->get('/logout', 'MainController', 'logout');
$router->get('/selectProfessor', 'MainController', 'selectProfessor');
$router->get('/selectProfessorToView', 'MainController', 'selectProfessorToView');
$router->get('/getHintForProfessorName', 'MainController', 'getHintForProfessorName');
$router->get('/professor/[-\w\d\?\!\.]+/judge', 'MainController', 'judgeProfessor');
//Admin
$router->get('/admin/dashboard', 'AdminController', 'dashboard');
$router->get('/admin/dashboard/reviewComments', 'AdminController', 'reviewComments');
$router->get('/admin/login', 'AdminController', 'login');
$router->get('/admin/logout', 'AdminController', 'logout');


/******** POST ********/
//Public
$router->post('/login', 'MainController', 'postlogin');
$router->post('/selectProfessor', 'MainController', 'postSelectProfessor');
$router->post('/viewProfessorByName', 'MainController', 'viewProfessorByName');
$router->post('/selectProfessorByName', 'MainController', 'selectProfessorByName');
$router->post('/judge', 'MainController', 'judge');
$router->post('/viewProfessor', 'MainController', 'viewProfessor');
$router->post('/report-comment', 'MainController', 'reportComment');
$router->post('/unreport-comment', 'MainController', 'unreportComment');
$router->post('/like-comment', 'MainController', 'likeComment');
$router->post('/unlike-comment', 'MainController', 'unlikeComment');
//Admin
$router->post('/admin/login', 'AdminController', 'postLogin');
$router->post('/admin/postReviewComments', 'AdminController', 'postReviewComments');




//See inside $router
//echo "<pre>";
//print_r($router);

$router->submit();

