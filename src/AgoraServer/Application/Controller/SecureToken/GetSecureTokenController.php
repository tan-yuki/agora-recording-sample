<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use AgoraServer\Application\Controller\RequestUriQueryToArrayTrait;
use AgoraServer\Application\Controller\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\AppCertificate;
use AgoraServer\Domain\Agora\AppId;
use AgoraServer\Domain\Agora\ChannelName;
use AgoraServer\Domain\Agora\UserId;
use AgoraServer\Domain\SecureToken\SecureToken;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetSecureTokenController
{
    use ResponseWithJsonTrait;
    use RequestUriQueryToArrayTrait;

    public function execute(Request $request, Response $response): Response
    {
        $queryParameters = $this->toArrayFromURI($request->getUri());

        // TODO: validation
        $appId = $queryParameters["appId"];
        $channelName = $queryParameters["channelName"];
        $userId = (int) $queryParameters["userId"];

        $token = SecureToken::create(
            new AppId($appId),
            // TODO: Read from environment variables
            new AppCertificate("abc"),
            new ChannelName($channelName),
            new UserId($userId),
        );

        return $this->withJson($response, ['token' => $token->toString()]);
    }
}