<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/products', function (Request $request, Response $response) {
        
    $database = new App\Database;

    $pdo = $database->connect();

    $stmt = $pdo->query("SELECT * FROM product");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $body = json_encode($data);

    $response->getBody()->write($body);
    
    return $response->withHeader('Content-Type', 'application/json');

});

$app->run();