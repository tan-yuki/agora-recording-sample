<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;
use Dotenv\Dotenv;

const ROOT_PATH = __DIR__ . '/..';

require ROOT_PATH . '/vendor/autoload.php';

$env = $_ENV['AGORA_APP_ENV'] ?? 'DEV';

// Load env file
// if production, set environment variables directly by Heroku console.
if ($env === 'DEV') {
    $dotenv = Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
}

// Initialize application
$initializer = new Initializer(new ContainerBuilder());
$app = $initializer->createApplication();

// Run api server
$app->run();
