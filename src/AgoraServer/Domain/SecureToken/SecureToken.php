<?php
declare(strict_types=1);

namespace AgoraServer\Domain\SecureToken;

final class SecureToken
{
    private string $dynamicKey;

    public function __construct(string $dynamicKey)
    {
        $this->dynamicKey = $dynamicKey;
    }

    public function value(): string
    {
        return $this->dynamicKey;
    }
}