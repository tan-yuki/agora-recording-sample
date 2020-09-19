<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class GetSecureTokenController
{
    public function execute(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode(['message' => 'Hello, world!']));
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}