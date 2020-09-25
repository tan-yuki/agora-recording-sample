<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Entity\RestfulAPI;

class CustomerId
{
    private string $customerId;
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }

    public function value(): string
    {
        return $this->customerId;
    }

}