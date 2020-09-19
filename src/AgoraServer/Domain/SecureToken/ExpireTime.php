<?php
declare(strict_types=1);


namespace AgoraServer\Domain\SecureToken;


/**
 * Class ExpireTime
 *
 * Tokenの有効期限。
 *
 * @see https://docs.agora.io/en/Agora%20Platform/token/#app-certificate
 * @package AgoraServer\Domain\SecureToken
 */
final class ExpireTime
{
    private int $currentTimeUnixTime;
    private int $expiredSeconds;

    private function __construct(int $currentTimeUnixTime, int $expiredSeconds)
    {
        $this->currentTimeUnixTime = $currentTimeUnixTime;
        $this->expiredSeconds = $expiredSeconds;
    }

    /**
     * @param int $currentTimeUnixTime 現在時刻のUnixTime.
     * @param int $expiredSeconds トークンを生成してから有効期限切れになるまでの時間
     * @return self
     */
    public static function create(int $currentTimeUnixTime, int $expiredSeconds)
    {
        return new self($currentTimeUnixTime, $expiredSeconds);
    }

    public function value(): int
    {
        return $this->currentTimeUnixTime + $this->expiredSeconds;
    }

}