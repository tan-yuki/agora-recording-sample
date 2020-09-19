<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora;


final class UserId
{
    private int $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function value(): int
    {
        return $this->userId;
    }


}