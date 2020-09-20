<?php
declare(strict_types=1);


namespace AgoraServer\Application\Middleware;


use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;

class HttpExceptionMiddleware implements MiddlewareInterface
{
    use ResponseWithJsonTrait;

    private ResponseFactoryInterface $responseFactory;
    private LoggerInterface $logger;

    public function __construct(ResponseFactoryInterface $responseFactory,
                                LoggerInterface $logger)
    {
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $httpException) {
            $this->logger->error($httpException);

            $response = $this->responseFactory->createResponse()->withStatus($httpException->getCode());
            return $this->withJson($response, [
                'message' => $httpException->getDescription(),
            ]);
        }
    }

}