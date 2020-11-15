<?php
declare(strict_types=1);


namespace AgoraServer\Application\Middleware;


use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\NotCreatedRecordingFileException;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\UnknownRecordingStopApiException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Exception;

class ExceptionHandleMiddleware implements MiddlewareInterface
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

    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpBadRequestException $httpException) {
            return $this->handleClientError($httpException->getDescription());
        } catch (NotCreatedRecordingFileException $e) {
            return $this->handleClientError($e->getMessage());
        } catch (UnknownRecordingStopApiException $e) {
            return $this->handleInternalError($e);
        } catch (Exception $exception) {
            return $this->handleInternalError($exception);
        }
    }

    private function handleClientError(string $message): ResponseInterface
    {
        $response = $this->responseFactory->createResponse()->withStatus(400);
        return $this->withJson($response, [
            'message' => $message,
        ]);
    }

    private function handleInternalError(Exception $e): ResponseInterface
    {
        $this->logger->error($e);

        $response = $this->responseFactory->createResponse()->withStatus(500);
        return $this->withJson($response, [
            'message' => $e->getMessage(),
        ]);
    }
}