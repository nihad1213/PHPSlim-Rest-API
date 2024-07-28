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

$app->post('/api/products', function (Request $request, Response $response) {
    // Read and decode the JSON body
    $data = json_decode($request->getBody()->getContents(), true);

    // Debugging: log the received data
    error_log('Received data: ' . print_r($data, true));

    // Validate data
    if (!isset($data['name']) || !isset($data['description']) || !isset($data['size'])) {
        $error = json_encode(["error" => "Missing required fields"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400);
    }

    if (empty($data['name']) || empty($data['description']) || !is_numeric($data['size'])) {
        $error = json_encode(["error" => "Invalid field values"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400);
    }

    $repository = $this->get(App\Repositories\ProductRepository::class);
    $id = $repository->create($data);

    $response->getBody()->write(json_encode(["id" => $id]));
    return $response->withHeader('Content-Type', 'application/json')
                    ->withStatus(201);
});


$app->put('/api/products/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    
    // Read and decode the JSON body
    $data = json_decode($request->getBody()->getContents(), true);

    // Debugging: log the received data
    error_log('Received data: ' . print_r($data, true));

    $repository = $this->get(App\Repositories\ProductRepository::class);
    $product = $repository->getByID((int) $id);

    if ($product === false) {
        $error = json_encode(["error" => "ID doesn't match"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(404);
    }

    // Validate data
    if (empty($data['name']) || empty($data['description']) || !isset($data['size'])) {
        $error = json_encode(["error" => "Missing required fields"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400);
    }

    if (!is_numeric($data['size'])) {
        $error = json_encode(["error" => "Invalid size value"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(400);
    }

    $updated = $repository->update((int) $id, $data);

    if ($updated) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(200);
    } else {
        $error = json_encode(["error" => "Failed to update product"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(500);
    }
});

$app->delete('/api/products/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];

    $repository = $this->get(App\Repositories\ProductRepository::class);
    $product = $repository->getByID((int) $id);

    if ($product === false) {
        $error = json_encode(["error" => "ID doesn't match"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(404);
    }

    $deleted = $repository->delete((int) $id);

    if ($deleted) {
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(204);
    } else {
        $error = json_encode(["error" => "Failed to delete product"]);
        $response->getBody()->write($error);
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus(500);
    }
});

$app->run();