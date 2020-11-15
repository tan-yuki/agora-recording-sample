<?php
declare(strict_types=1);

namespace AgoraServer\Application;

use AgoraServer\Application\Route\Route;
use AgoraServer\Infrastructure\Env\EnvironmentName;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Slim\App;
use DI\DependencyException;
use DI\NotFoundException;

final class Initializer
{
    private EnvironmentName $envName;
    private Config $config;
    private ContainerBuilder $containerBuilder;

    public function __construct(EnvironmentName $envName,
                                ContainerBuilder $containerBuilder,
                                Config $config)
    {
        $this->envName = $envName;
        $this->containerBuilder = $containerBuilder;
        $this->config = $config;
    }

    /**
     * @return App
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public function createApplication(): App
    {
        $app = $this->config->apply($this->envName, $this->containerBuilder);

        /** @var Route $router */
        $route = $app->getContainer()->get(Route::class);
        $route->bind();

        return $app;
    }
}