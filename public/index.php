<?php

use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Initialize application
$initializer = new Initializer(new ContainerBuilder());
$app = $initializer->createApplication();

// Run api server
$app->run();
