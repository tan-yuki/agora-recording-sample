<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;

use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class AwsS3BucketNameFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): AWSS3BucketName
    {
        return new AwsS3BucketName($this->env->getAWSS3BucketName());
    }

}