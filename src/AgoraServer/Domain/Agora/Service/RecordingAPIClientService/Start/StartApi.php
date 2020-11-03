<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start;

use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Project\SecureToken;
use AgoraServer\Domain\Agora\Entity\Recording\AwsS3BucketName;
use AgoraServer\Domain\Agora\Entity\Recording\AwsS3BucketNameFactory;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentials;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentialsFactory;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\AgoraRecordingAPIClient;

class StartApi
{
    private AgoraRecordingAPIClient $client;
    private AwsS3BucketName $bucketName;
    private AwsCredentials $awsCredentials;

    public function __construct(AgoraRecordingAPIClient $client,
                                AwsS3BucketNameFactory $awsS3BucketNameFactory,
                                AwsCredentialsFactory $awsCredentialsFactory)
    {
        $this->client = $client;
        $this->bucketName = $awsS3BucketNameFactory->create();
        $this->awsCredentials = $awsCredentialsFactory->create();
    }

    public function __invoke(ResourceId $resourceId,
                             ChannelName $channelName,
                             UserId $userId,
                             SecureToken $token): StartApiResponse
    {
        $responseJson = $this->client->callAgoraApi(
            sprintf('/resourceid/%s/mode/mix/start', $resourceId->value()),
            [
                'cname' => $channelName->value(),
                'uid' => (string) $userId->value(),
                'clientRequest' => [
                    'token' => $token->value(),
                    'recordingConfig' => [
                        'channelType' => 1,    // Live mode only.
                    ],
                    'storageConfig' => [
                        'vendor' => 1, // Amazon S3
                        'region' => 10, // AP_NORTHEAST_1
                        'bucket' => $this->bucketName->value(),
                        'accessKey' => $this->awsCredentials->getAccessToken(),
                        'secretKey' => $this->awsCredentials->getSecretToken(),
                    ]
                ],
            ]);

        return new StartApiResponse($responseJson);
    }

}