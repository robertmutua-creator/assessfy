<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use App\Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/login', 'HomeController@login');
$router->post('/login', 'HomeController@login');

$router->get('/setup', 'SetupController@tables');
$router->get('/seed', 'SetupController@seed');

$router->get('/dashboard', 'DashboardController@index');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
