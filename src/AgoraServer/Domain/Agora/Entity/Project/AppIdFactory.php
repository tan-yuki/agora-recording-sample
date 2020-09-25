<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Entity\Project;

use AgoraServer\Domain\Agora\Entity\Project\AppId;
use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class AppIdFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): AppId
    {
        return new AppId($this->env->getAppId());
    }

}