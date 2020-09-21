<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\AppCertificate;
use AgoraServer\Domain\Agora\AppId;
use AgoraServer\Domain\Agora\ChannelName;
use AgoraServer\Domain\Agora\UserId;
use AgoraServer\Domain\SecureToken\SecureToken;
use AgoraServer\Infrastructure\Env\Exception\EnvironmentKeyNotFoundException;
use AgoraServer\Infrastructure\Env\EnvironmentVariable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

final class GetSecureTokenController
{
    use ResponseWithJsonTrait;

    private GetSecureTokenRequest $request;
    private EnvironmentVariable $env;

    public function __construct(GetSecureTokenRequest $request, EnvironmentVariable $env)
    {
        $this->request = $request;
        $this->env = $env;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return Response
     * @throws HttpBadRequestException
     * @throws EnvironmentKeyNotFoundException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $validParams = $this->request->validate($request);

        $token = SecureToken::create(
            new AppId($validParams[GetSecureTokenRequest::PARAM_APP_ID]),
            new AppCertificate($this->env->getAppCertificate()),
            new ChannelName($validParams[GetSecureTokenRequest::PARAM_CHANNEL_NAME]),
            new UserId($validParams[GetSecureTokenRequest::PARAM_USER_ID]),
        );

        return $this->withJson($response, ['token' => $token->toString()]);
    }
}