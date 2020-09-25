<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Entity\RestfulAPI;

class CustomerSecret
{
    private string $customerSecret;
    public function __construct(string $customerSecret)
    {
        $this->customerSecret = $customerSecret;
    }

    public function value(): string
    {
        return $this->customerSecret;
    }

}