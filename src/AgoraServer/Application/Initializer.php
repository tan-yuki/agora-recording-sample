<?php
declare(strict_types=1);

namespace AgoraServer\Application;

use AgoraServer\Application\Route\Route;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Slim\App;
use DI\DependencyException;
use DI\NotFoundException;

final class Initializer
{
    private Config $config;
    private ContainerBuilder $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder,
                                Config $config)
    {
        $this->config = $config;
        $this->containerBuilder = $config->addDefinitions($containerBuilder);
    }

    /**
     * @return App
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function createApplication(): App
    {
        $container = $this->containerBuilder->build();

        $app = Bridge::create($container);
        $app = $this->config->addMiddleware($app, $container);

        /** @var Route $router */
        $route = $container->get(Route::class);
        $route->bind();

        return $app;
    }
}