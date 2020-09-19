<?php
declare(strict_types=1);

namespace AgoraServer\Domain\SecureToken;

use Agora\AgoraDynamicKey\DynamicKey5;
use AgoraServer\Domain\Agora\AppCertificate;
use AgoraServer\Domain\Agora\AppId;
use AgoraServer\Domain\Agora\ChannelName;
use AgoraServer\Domain\Agora\UserId;

final class SecureToken
{
    /**
     * Tokenの有効期限（秒）
     */
    const TOKEN_EXPIRED_PERIOD = 21600; // 6 * 60 * 60 = 6時間

    private string $dynamicKey;

    private function __construct(string $dynamicKey)
    {
        $this->dynamicKey = $dynamicKey;
    }

    public static function create(AppId $appId,
                                  AppCertificate $appCertificate,
                                  ChannelName $channelName,
                                  UserId $userId): self
    {
        $currentTimeUnixTime = time();
        $randomInt = rand(100000000, 999999999);

        $expireTime = ExpireTime::create($currentTimeUnixTime, self::TOKEN_EXPIRED_PERIOD);
        $dynamicKey = DynamicKey5::generateDynamicKey(
            $appId->value(),
            $appCertificate->value(),
            $channelName->value(),
            $currentTimeUnixTime,
            $randomInt,
            $userId->value(),
            $expireTime->value(),
            DynamicKey5::MEDIA_CHANNEL_SERVICE,
            []);

        return new self($dynamicKey);
    }

    public function toString(): string
    {
        return $this->dynamicKey;
    }

}