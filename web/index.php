<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// DI Container
$container = new Container();

// Set DI Container
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/', function(Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode(['message' => 'Hello, world!']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
