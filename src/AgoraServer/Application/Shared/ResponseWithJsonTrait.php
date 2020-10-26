<?php
declare(strict_types=1);

namespace AgoraServer\Application\Shared;

use Psr\Http\Message\ResponseInterface;

trait ResponseWithJsonTrait
{
    private function withJson(ResponseInterface $response, array $body): ResponseInterface
    {
        $response->getBody()->write(json_encode($body, JSON_UNESCAPED_UNICODE));

        return $response->withHeader('Content-Type', 'application/json');
    }
}