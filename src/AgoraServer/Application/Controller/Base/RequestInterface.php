<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\Base;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

interface RequestInterface
{
    /**
     * Validate server request.
     *
     * If this request has errors, throw HttpBadRequestException.
     * If no errors, return parameters as primitive array key pair.
     *
     * @param ServerRequestInterface $request
     * @return array Parameter key value pair.
     * @throws HttpBadRequestException
     */
    public function validate(ServerRequestInterface $request): array;
}