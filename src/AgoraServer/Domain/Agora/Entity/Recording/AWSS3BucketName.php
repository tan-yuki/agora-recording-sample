<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;


class AWSS3BucketName
{
    private string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function value(): string
    {
        return $this->name;
    }

}