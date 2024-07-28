<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/products', function (Request $request, Response $response) {
    
    $dsn = "mysql:host127.0.0.1;dbname=products_db;charset=utf8";

    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $stmt = $pdo->query("SELECT * FROM products_db");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $body = json_encode($data);

    $response->getBody()->write($body);
    
    return $response->withHeader('Content-Type', 'application/json');

});

$app->run();