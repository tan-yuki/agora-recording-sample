<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Project;


class AppCertificate
{
    private string $appCertificate;
    public function __construct(string $appCertificate)
    {
        $this->appCertificate = $appCertificate;
    }

    public function value(): string
    {
        return $this->appCertificate;
    }

}