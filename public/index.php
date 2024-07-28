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

$app->run();