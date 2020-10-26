<?php
declare(strict_types=1);


namespace AgoraServer\Application\Middleware;


use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Infrastructure\Env\EnvironmentVariable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class CORSMiddleware implements MiddlewareInterface
{
    use ResponseWithJsonTrait;

    private ResponseFactoryInterface $responseFactory;
    private EnvironmentVariable $env;
    private LoggerInterface $logger;

    public function __construct(ResponseFactoryInterface $responseFactory,
                                EnvironmentVariable $env,
                                LoggerInterface $logger)
    {
        $this->responseFactory = $responseFactory;
        $this->env = $env;
        $this->logger = $logger;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->env->getHeaderAccessControlAllowOrigin())
            ->withHeader(
                'Access-Control-Allow-Headers',
                'X-Requested-With, Content-Type, Accept, Origin, Authorization'
            )
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }

}