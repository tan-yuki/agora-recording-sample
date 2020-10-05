<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService;

use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentials;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentialsFactory;
use AgoraServer\Domain\Agora\Entity\Project\SecureTokenFactory;

class StartApi
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

    public function __invoke(ResourceId $resourceId, ChannelName $channelName, UserId $userId): RecordingId
    {
        $responseJson = $this->client->callAgoraApi(
            sprintf('/resourceid/%s/mode/mix/start', $resourceId->value()),
            [
                'cname' => $channelName->value(),
                'uid' => (string) $userId->value(),
                'clientRequest' => [
                    // TODO: Specify token
                    //'token' => $this->secureTokenFactory->create($channelName, $userId),
                    'recordingConfig' => [
                        'channelType' => 1,    // Live mode only.
                        'decryptionMode' => 3, // AES-256, XTS mode.
                        'secret' => 'password',
                    ],
                    'storageConfig' => [
                        'vendor' => 1, // Amazon S3
                        'region' => 10, // AP_NORTHEAST_1
                        'bucket' => 'agora-recording-sample',
                        'accessKey' => $this->awsCredentials->getAccessToken(),
                        'secretKey' => $this->awsCredentials->getSecretToken(),
                    ]
                ],
            ]);

        return new RecordingId($responseJson['sid']);
    }

}