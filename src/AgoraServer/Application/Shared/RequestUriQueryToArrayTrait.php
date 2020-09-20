<?php
declare(strict_types=1);


namespace AgoraServer\Application\Shared;


use Psr\Http\Message\UriInterface;

trait RequestUriQueryToArrayTrait
{
    public function toArrayFromURI(UriInterface $uri): array
    {
        parse_str($uri->getQuery(), $get_array);
        return $get_array;
    }

}