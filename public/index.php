<?php

use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$initializer = new Initializer($builder);
$initializer->execute();
