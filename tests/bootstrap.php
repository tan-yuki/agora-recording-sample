<?php
use AgoraServer\Application\Initializer;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load env file from sample file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

