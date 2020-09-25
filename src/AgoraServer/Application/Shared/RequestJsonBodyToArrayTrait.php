<?php
declare(strict_types=1);


namespace AgoraServer\Application\Shared;

use Psr\Http\Message\ServerRequestInterface;

trait RequestJsonBodyToArrayTrait
{
    public function toArrayFromJsonBody(ServerRequestInterface $request): array
    {
        $body = (string) $request->getBody();

        return json_decode($body, true);
    }
}