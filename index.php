<?php

require __DIR__."/vendor/autoload.php";

use \CoffeeCode\Router\Router;

$route = new Router(url(), ':');

$route->namespace('Source\App');

#posts
$route->get('/', 'WebController:home');
$route->post('/cadastrar', 'WebController:cadastrar');

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    echo 'error';
}
