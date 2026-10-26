<?php

use App\Core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

// $router->get('/', 'Hello!');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
