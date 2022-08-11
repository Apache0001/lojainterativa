<?php

require __DIR__."/../vendor/autoload.php";

use \CoffeeCode\Router\Router;

$route = new Router(url(), ':');


##############
#### API #####
##############
$route->namespace('Source\App\Api');

#produtos
$route->get('/produto', 'Produto:getProdutos');
$route->get('/produto/{id}', 'Produto:getProduto');
$route->post('/produto', 'Produto:postProduto');
$route->put('/produto/{id}', 'Produto:updateProduto');
$route->delete('/produto/{id}', 'Produto:deleteProduto');

#categorias
$route->get('/categoria', 'Categoria:getCategorias');
$route->get('/categoria/{id}', 'Categoria:getCategoria');
$route->post('/categoria', 'Categoria:postCategoria');
$route->put('/categoria/{id}', 'Categoria:updateCategoria');
$route->delete('/categoria/{id}', 'Categoria:deleteCategoria');

#fabricante
$route->get('/fabricante', 'Fabricante:getFabricantes');
$route->get('/fabricante/{id}', 'Fabricante:getFabricante');
$route->post('/fabricante', 'Fabricante:postFabricante');
$route->delete('/fabricante/{id}', 'Fabricante:deleteFabricante');
$route->put('/fabricante/{id}', 'Fabricante:updateFabricante');



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
