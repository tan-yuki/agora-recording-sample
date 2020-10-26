<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire;

use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\AgoraRecordingAPIClient;

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

    public function __invoke(ChannelName $channelName, UserId $userId): AcquireApiResponse
    {
        $responseJson = $this->client->callAgoraApi('/acquire', [
            'cname' => $channelName->value(),
            'uid' => (string) $userId->value(),
            'clientRequest' => [
                'resourceExpiredHour' => 24,
            ],
        ]);

        return new AcquireApiResponse($responseJson);
    }

}