<?php
declare(strict_types=1);


namespace AgoraServer\Application;


use AgoraServer\Application\Middleware\CORSMiddleware;
use AgoraServer\Application\Middleware\ExceptionHandleMiddleware;
use AgoraServer\Infrastructure\Env\EnvironmentName;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Formatter\LineFormatter;
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
    /**
     * Define logger.
     *
     * @param EnvironmentName $environmentName
     * @return LoggerInterface
     */
    private function defineLogger(EnvironmentName $environmentName): LoggerInterface
    {
        $logger = new Logger('agora');
        $handler = new StreamHandler('php://stderr', $environmentName->getLoggerLevel());

        if ($environmentName->equals(EnvironmentName::DEV())) {
            // Allow inline line break in debug environment.
            // see: https://stepanoff.org/wordpress/2017/11/13/allow-inline-line-breaks-for-monolog/
            $lineFormatter = new LineFormatter(
                null, null, true, true
            );

            $handler->setFormatter($lineFormatter);
        }

        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * Define DI.
     *
     * @param LoggerInterface  $logger
     * @param ContainerBuilder $builder
     * @return ContainerBuilder
     */
    private function defineDiDefinitions(LoggerInterface $logger, ContainerBuilder $builder): ContainerBuilder
    {
        $builder->addDefinitions([
            LoggerInterface::class => function () use ($logger) {
                return $logger;
            },
            ResponseFactoryInterface::class => function () {
                return new ResponseFactory();
            },
            Client::class => function() use ($logger) {
                $stack = HandlerStack::create();
                $stack->push(
                    Middleware::log(
                        $logger,
                        new MessageFormatter(<<<EOL

Call internal API.
>>> [Request]
{request}

<<< [Response]
{response}

EOL),
                        'debug'
                    )
                );

                return new Client([
                    'handler' => $stack,
                ]);
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
    private function defineSlimMiddleware(App $app, Container $container): App
    {
        $app->add($container->get(ExceptionHandleMiddleware::class));
        $app->add($container->get(CORSMiddleware::class));

        return $app;
    }

    /**
     * @param EnvironmentName  $environmentName
     * @param ContainerBuilder $builder
     * @return App
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public function apply(EnvironmentName $environmentName, ContainerBuilder $builder): App
    {
        // Define DI.
        $builder = $this->defineDiDefinitions($this->defineLogger($environmentName), $builder);
        $container = $builder->build();

        // Then, create slim application.
        $app = Bridge::create($container);

        // Then, add middleware for Slim application.
        $app = $this->defineSlimMiddleware($app, $container);

        return $app;
    }
}