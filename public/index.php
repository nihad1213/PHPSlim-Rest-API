<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DI\Container;
use DI\ContainerBuilder;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/api/products', function (Request $request, Response $response) {
    
    $repository = $this->get(App\Repositories\ProductRepository::class);
    $data = $repository->getAll();

    $body = json_encode($data);

    $response->getBody()->write($body);
    
    return $response->withHeader('Content-Type', 'application/json');

});

$app->get('/api/products/{id: [0-9]+}', function (Request $request, Response $response, array $args) {

    $id = $args['id'];

    $repository = $this->get(App\Repositories\ProductRepository::class);
    $product = $repository->getByID((int) $id);

    if ($product === false) {
        $error = json_encode(["error" => "ID doesnt match"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(404);
    }

    $body = json_encode($product);

    $response->getBody()->write($body);
    return $response -> withHeader('Content-Type', 'application/json');

});

$app->run();