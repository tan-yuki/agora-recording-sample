<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Base;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface;
}