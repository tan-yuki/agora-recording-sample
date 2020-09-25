<?php
declare(strict_types=1);

namespace AgoraServer\Infrastructure\AgoraRecordingAPIClient;

use AgoraServer\Domain\AppId;
use AgoraServer\Domain\ChannelName;
use AgoraServer\Domain\AppIdFactory;
use AgoraServer\Domain\Recording\RecordingId;
use AgoraServer\Domain\Recording\ResourceId;
use AgoraServer\Domain\RestfulAPI\AuthCredentialKey;
use AgoraServer\Domain\RestfulAPI\AuthCredentialKeyFactory;
use AgoraServer\Domain\UserId;
use AgoraServer\Domain\Recording\AwsCredentials;
use AgoraServer\Domain\Recording\AwsCredentialsFactory;
use AgoraServer\Domain\SecureToken\SecureTokenFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class StartApi
{
    private AppId $appId;
    private AuthCredentialKey $authCredentialKey;
    private SecureTokenFactory $secureTokenFactory;
    private AwsCredentials $awsCredentials;

    public function __construct(AppIdFactory $appIdFactory,
                                AuthCredentialKeyFactory $authCredentialKeyFactory,
                                SecureTokenFactory  $secureTokenFactory,
                                AwsCredentialsFactory $awsCredentialsFactory)
    {
        $this->appId = $appIdFactory->create();
        $this->authCredentialKey = $authCredentialKeyFactory->create();
        $this->secureTokenFactory = $secureTokenFactory;
        $this->awsCredentials = $awsCredentialsFactory->create();
    }

    public function __invoke(ResourceId $resourceId, ChannelName $channelName, UserId $userId): RecordingId
    {
        $client = new Client();
        $request = new Request(
            'POST',
            sprintf('https://api.agora.io/v1/apps/%s/cloud_recording/resourceid/%s/mode/mix/start',
                $this->appId->value(),
                $resourceId->value(),
            ),
            [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Basic ' . $this->authCredentialKey->value()
            ],
            json_encode([
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
            ], JSON_UNESCAPED_UNICODE));

        $response = $client->send($request);
        $responseJson = json_decode((string) $response->getBody(), true);

        return new RecordingId($responseJson['sid']);
    }

}