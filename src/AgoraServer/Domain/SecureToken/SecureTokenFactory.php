<?php
declare(strict_types=1);

namespace AgoraServer\Domain\SecureToken;

use Agora\AgoraDynamicKey\DynamicKey5;
use AgoraServer\Domain\AppCertificateFactory;
use AgoraServer\Domain\ChannelName;
use AgoraServer\Domain\AppIdFactory;
use AgoraServer\Domain\UserId;

final class SecureTokenFactory
{
    /**
     * Tokenの有効期限（秒）
     */
    const TOKEN_EXPIRED_PERIOD = 21600; // 6 * 60 * 60 = 6時間

    private AppIdFactory $appIdFactory;
    private AppCertificateFactory $appCertificateFactory;

    public function __construct(AppIdFactory $appIdFactory,
                                AppCertificateFactory $appCertificateFactory)
    {
        $this->appIdFactory = $appIdFactory;
        $this->appCertificateFactory = $appCertificateFactory;
    }

    public function create(ChannelName $channelName, UserId $userId): SecureToken
    {
        $currentTimeUnixTime = time();
        $randomInt = rand(100000000, 999999999);

        $expireTime = ExpireTime::create($currentTimeUnixTime, self::TOKEN_EXPIRED_PERIOD);
        $dynamicKey = DynamicKey5::generateDynamicKey(
            $this->appIdFactory->create()->value(),
            $this->appCertificateFactory->create()->value(),
            $channelName->value(),
            $currentTimeUnixTime,
            $randomInt,
            $userId->value(),
            $expireTime->value(),
            DynamicKey5::MEDIA_CHANNEL_SERVICE,
            []);

        return new SecureToken($dynamicKey);
    }

}