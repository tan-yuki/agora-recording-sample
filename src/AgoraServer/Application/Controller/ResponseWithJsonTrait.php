<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller;


use Slim\Psr7\Response;

trait ResponseWithJsonTrait
{
    private function withJson(Response $response, array $body): Response
    {
        $response->getBody()->write(json_encode($body, JSON_UNESCAPED_UNICODE));
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}