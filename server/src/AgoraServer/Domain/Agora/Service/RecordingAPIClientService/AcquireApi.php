<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService;

use AgoraServer\Domain\Agora\Entity\Project\AppId;
use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Project\AppIdFactory;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKey;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKeyFactory;
use AgoraServer\Domain\Agora\Entity\UserId;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class AcquireApi
 * @package AgoraServer\Infrastructure\AgoraRecordingAPIClient
 */
class AcquireApi
{
    private AgoraRecordingAPIClient $client;

    public function __construct(AgoraRecordingAPIClient $client)
    {
        $this->client = $client;
    }

    public function __invoke(ChannelName $channelName, UserId $userId): ResourceId
    {
        $responseJson = $this->client->callAgoraApi('/acquire', [

            'cname' => $channelName->value(),
            'uid' => (string) $userId->value(),
            'clientRequest' => [
                'resourceExpiredHour' => 24,
            ],
        ]);

        return new ResourceId($responseJson['resourceId']);
    }

}