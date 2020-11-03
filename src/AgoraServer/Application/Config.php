<?php
declare(strict_types=1);


namespace AgoraServer\Application;


use AgoraServer\Application\Middleware\CORSMiddleware;
use AgoraServer\Application\Middleware\ExceptionHandleMiddleware;
use DI\Container;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;
use DI\DependencyException;
use DI\NotFoundException;

class Config
{

    public function addDefinitions(ContainerBuilder $builder): ContainerBuilder
    {
        $builder->addDefinitions([
            LoggerInterface::class => function () {
                $logger = new Logger('agora');
                $logger->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));

                return $logger;
            },
            ResponseFactoryInterface::class => function () {
                return new ResponseFactory();
            },
        ]);

        return $builder;
    }

    /**
     * Add Slim application middleware.
     *
     * @param App       $app
     * @param Container $container
     * @return App
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function addMiddleware(App $app, Container $container): App
    {
        return $app
            ->addMiddleware($container->get(CORSMiddleware::class))
            ->addMiddleware($container->get(ExceptionHandleMiddleware::class));
    }
}