<?php

require __DIR__."/../vendor/autoload.php";

use \CoffeeCode\Router\Router;

$route = new Router(url(), ':');


##############
#### WEB #####
##############




##############
#### API #####
##############
$route->namespace('Source\App\Api');

#users
$route->get('/users', 'User:getUsers');
$route->get('/users/{id}', 'User:getUser');
$route->post('/users', 'User:postUser');
$route->put('/users/{id}', 'User:updateUser');
$route->delete('/users/{id}', 'User:deleteUser');

#posts
$route->get('/posts', 'Posts:getPosts');
$route->get('/posts/{id}', 'Posts:getPost');
$route->post('/posts', 'Posts:postPost');
$route->delete('/posts', 'Posts:deletePost');
$route->put('/posts', 'Posts:updatePost');

#categorias
$route->get('/categories', 'Categories:getCategories');
$route->get('/categories/{id}', 'Categories:getCategory');


/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
