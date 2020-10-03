<?php

use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;

require __DIR__ . '/../vendor/autoload.php';

$initializer = new Initializer(new ContainerBuilder());
$app = $initializer->createApplication();
$app->run();
