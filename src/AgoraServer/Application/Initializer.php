<?php
declare(strict_types=1);

namespace AgoraServer\Application;

use AgoraServer\Application\Route\Route;
use Slim\App;

final class Initializer
{
    private App $app;
    private Route $route;

    public function __construct(App $app, Route $route)
    {
        $this->app = $app;
        $this->route = $route;
    }

    public function execute(): void
    {
        // Route
        $this->route->bind();

        // Run server
        $this->app->run();
    }
}