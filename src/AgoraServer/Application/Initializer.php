<?php
declare(strict_types=1);

namespace AgoraServer\Application;

use AgoraServer\Application\Middleware\HttpExceptionMiddleware;
use AgoraServer\Application\Route\Route;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;

final class Initializer
{
    private ContainerBuilder $builder;

    public function __construct(ContainerBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function execute(): void
    {
        $this->builder->addDefinitions([
            LoggerInterface::class => function() {
                return new Logger('agora');
            },
            ResponseFactoryInterface::class => function() {
                return new ResponseFactory();
            },
        ]);

        $container = $this->builder->build();
        $app = Bridge::create($container);
        $app->addErrorMiddleware(true, true, true);
        $app->addMiddleware($container->get(HttpExceptionMiddleware::class));

        /** @var Route $router */
        $route = $container->get(Route::class);
        $route->bind();;

        $app->run();
    }
}