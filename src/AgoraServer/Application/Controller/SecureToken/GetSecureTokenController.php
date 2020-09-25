<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Entity\Project\SecureTokenFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

final class GetSecureTokenController
{
    use ResponseWithJsonTrait;

    private GetSecureTokenRequest $request;
    private SecureTokenFactory $secureTokenFactory;

    public function __construct(GetSecureTokenRequest $request,
                                SecureTokenFactory $secureTokenFactory)
    {
        $this->request = $request;
        $this->secureTokenFactory = $secureTokenFactory;
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

        $secureToken = $this->secureTokenFactory->create(
            new ChannelName($validParams[GetSecureTokenRequest::PARAM_CHANNEL_NAME]),
            new UserId($validParams[GetSecureTokenRequest::PARAM_USER_ID]),
        );

        return $this->withJson($response, ['token' => $secureToken->value()]);
    }
}