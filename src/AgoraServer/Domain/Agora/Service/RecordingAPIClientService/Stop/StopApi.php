<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop;

use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentials;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentialsFactory;
use AgoraServer\Domain\Agora\Entity\Project\SecureTokenFactory;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\AgoraRecordingAPIClient;

class StopApi
{
    private AgoraRecordingAPIClient $client;
    private SecureTokenFactory $secureTokenFactory;
    private AwsCredentials $awsCredentials;

    public function __construct(AgoraRecordingAPIClient $client,
                                SecureTokenFactory  $secureTokenFactory,
                                AwsCredentialsFactory $awsCredentialsFactory)
    {
        $this->client = $client;
        $this->secureTokenFactory = $secureTokenFactory;
        $this->awsCredentials = $awsCredentialsFactory->create();
    }

    public function __invoke(ResourceId $resourceId,
                             RecordingId $recordingId,
                             ChannelName $channelName,
                             UserId $userId): StopApiResponse
    {
        $responseJson = $this->client->callAgoraApi(
            sprintf('/resourceid/%s/sid/%s/mode/mix/stop', $resourceId->value(), $recordingId->value()),
            [
                'cname' => $channelName->value(),
                'uid' => (string) $userId->value(),
                'clientRequest' => (object) [],
            ]);

        return new StopApiResponse($responseJson);
    }

}