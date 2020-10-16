<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Entity\RestfulAPI;

use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class CustomerIdFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): CustomerId
    {
        return new CustomerId($this->env->getRestfulAPICustomerId());
    }

}