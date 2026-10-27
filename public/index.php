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
$router->get('/logout', 'HomeController@logout');

$router->get('/setup', 'SetupController@tables');
$router->get('/seed', 'SetupController@seed');

$router->get('/dashboard', 'DashboardController@index');
$router->get('/admin/schools', 'SchoolsController@index');
$router->get('/admin/schools/edit/{id}', 'SchoolsController@edit');
$router->post('/admin/schools/save', 'SchoolsController@save');
$router->post('/admin/schools/delete/{id}', 'SchoolsController@delete');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
