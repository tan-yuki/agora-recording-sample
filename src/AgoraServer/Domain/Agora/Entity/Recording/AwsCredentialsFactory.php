<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;

use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class AwsCredentialsFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): AwsCredentials
    {
        return new AwsCredentials(
            $this->env->getRecordingAwsAccessToken(),
            $this->env->getRecordingAwsSecretToken()
        );
    }

}