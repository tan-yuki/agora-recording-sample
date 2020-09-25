<?php
declare(strict_types=1);

namespace AgoraServer\Domain\RestfulAPI;

use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class CustomerSecretFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): CustomerSecret
    {
        return new CustomerSecret($this->env->getRestfulAPICustomerSecret());
    }

}