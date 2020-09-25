<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Recording;


class ResourceId
{
    private string $resourceId;
    public function __construct(string $resourceId)
    {
        $this->resourceId = $resourceId;
    }

    public function value(): string
    {
        return $this->resourceId;
    }
}