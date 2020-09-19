<?php

use DI\Container;
use DI\Bridge\Slim\Bridge;
use AgoraServer\Application\Initializer;

require __DIR__ . '/../vendor/autoload.php';

// DI Container
$container = new Container();
$app = Bridge::create($container);

/** @var Initializer $initializer */
$initializer = $container->get(Initializer::class);
$initializer->execute();
