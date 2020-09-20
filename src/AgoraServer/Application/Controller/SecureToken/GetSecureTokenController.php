<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\AppCertificate;
use AgoraServer\Domain\Agora\AppId;
use AgoraServer\Domain\Agora\ChannelName;
use AgoraServer\Domain\Agora\UserId;
use AgoraServer\Domain\SecureToken\SecureToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

final class GetSecureTokenController
{
    use ResponseWithJsonTrait;

    private GetSecureTokenRequest $request;

    public function __construct(GetSecureTokenRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return Response
     * @throws HttpBadRequestException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $validParams = $this->request->validate($request);

        $token = SecureToken::create(
            new AppId($validParams[GetSecureTokenRequest::PARAM_APP_ID]),
            // TODO: Read from environment variables
            new AppCertificate("abc"),
            new ChannelName($validParams[GetSecureTokenRequest::PARAM_CHANNEL_NAME]),
            new UserId($validParams[GetSecureTokenRequest::PARAM_USER_ID]),
        );

        return $this->withJson($response, ['token' => $token->toString()]);
    }
}