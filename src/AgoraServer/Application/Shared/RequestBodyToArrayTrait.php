<?php
declare(strict_types=1);


namespace AgoraServer\Application\Shared;


use Psr\Http\Message\ServerRequestInterface;

trait RequestBodyToArrayTrait
{
    protected function toArrayFromRequestBody(ServerRequestInterface $request): array
    {
        return json_decode((string) $request->getBody(), true);
    }

}