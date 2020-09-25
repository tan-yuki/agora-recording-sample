<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Project;


final class AppId
{
    private string $appId;
    public function __construct(string $appId)
    {
        $this->appId = $appId;
    }

    public function value(): string
    {
        return $this->appId;
    }

}