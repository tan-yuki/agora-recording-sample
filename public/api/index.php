<?php

use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;
use Dotenv\Dotenv;

require __DIR__ . '/../../vendor/autoload.php';

// Load env file
// if production, set environment variables directly by Heroku console.
if ($_ENV['AGORA_APP_ENV'] !== 'PRODUCTION') {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
}

// Initialize application
$initializer = new Initializer(new ContainerBuilder());
$app = $initializer->createApplication();

// Run api server
$app->run();
