<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Entity\Project;

use Agora\AgoraDynamicKey\DynamicKey5;
use AgoraServer\Domain\Agora\Entity\Project\AppCertificateFactory;
use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Project\AppIdFactory;
use AgoraServer\Domain\Agora\Entity\UserId;

final class SecureTokenFactory
{
    /**
     * Tokenの有効期限（秒）
     */
    const TOKEN_EXPIRED_PERIOD = 21600; // 6 * 60 * 60 = 6時間

    private AppId $appId;
    private AppCertificate $appCertificate;

    public function __construct(AppIdFactory $appIdFactory,
                                AppCertificateFactory $appCertificateFactory)
    {
        $this->appId = $appIdFactory->create();
        $this->appCertificate = $appCertificateFactory->create();
    }

    public function create(ChannelName $channelName, UserId $userId): SecureToken
    {
        $currentTimeUnixTime = time();
        $randomInt = rand(100000000, 999999999);

        $expireTime = SecureTokenExpireTime::create($currentTimeUnixTime, self::TOKEN_EXPIRED_PERIOD);
        $dynamicKey = DynamicKey5::generateDynamicKey(
            $this->appId->value(),
            $this->appCertificate->value(),
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