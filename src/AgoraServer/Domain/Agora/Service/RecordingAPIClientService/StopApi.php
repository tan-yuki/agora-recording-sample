<?php
declare(strict_types=1);

namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService;

use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentials;
use AgoraServer\Domain\Agora\Entity\Recording\AwsCredentialsFactory;
use AgoraServer\Domain\Agora\Entity\Project\SecureTokenFactory;

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

    /**
     * @param ResourceId  $resourceId
     * @param RecordingId $recordingId
     * @param ChannelName $channelName
     * @param UserId      $userId
     * @return array
     *              [UploadingStatus, UploadFile[]]
     */
    public function __invoke(ResourceId $resourceId, RecordingId $recordingId, ChannelName $channelName, UserId $userId): array
    {
        $responseJson = $this->client->callAgoraApi(
            sprintf('/resourceid/%s/sid/%s/mode/mix/stop', $resourceId->value(), $recordingId->value()),
            [
                'cname' => $channelName->value(),
                'uid' => (string) $userId->value(),
                'clientRequest' => (object) [],
            ]);

        $serverResponse = $responseJson['serverResponse'];

        return [
            new UploadingStatus($serverResponse['uploadingStatus']),
            array_map(function($file) {
                return new UploadFile(
                    $file['filename'],
                    new UserId($file['uid']),
                    $file['sliceStartTime']
                );
            }, $serverResponse['fileList']),
        ];
    }

}