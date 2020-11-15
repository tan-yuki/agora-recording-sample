<?php
declare(strict_types=1);

use Dotenv\Dotenv;
use DI\ContainerBuilder;
use AgoraServer\Application\Initializer;
use AgoraServer\Application\Config;
use AgoraServer\Infrastructure\Env\EnvironmentName;

const ROOT_PATH = __DIR__ . '/..';

require ROOT_PATH . '/vendor/autoload.php';

// Load env file.
// if `.env` file exits, load this file.
// (if production, set environment variables directly by Heroku console.)
if (file_exists(ROOT_PATH . '/.env')) {
    $dotenv = Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
}

$envName = new EnvironmentName($_ENV['AGORA_APP_ENV'] ?? 'DEV');

// Initialize application
$initializer = new Initializer($envName, new ContainerBuilder(), new Config());
$app = $initializer->createApplication();

// Run api server
$app->run();
