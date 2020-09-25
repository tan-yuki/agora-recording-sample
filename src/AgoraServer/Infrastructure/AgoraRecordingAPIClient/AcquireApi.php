<?php
declare(strict_types=1);

namespace AgoraServer\Infrastructure\AgoraRecordingAPIClient;

use AgoraServer\Domain\AppId;
use AgoraServer\Domain\ChannelName;
use AgoraServer\Domain\AppIdFactory;
use AgoraServer\Domain\Recording\ResourceId;
use AgoraServer\Domain\RestfulAPI\AuthCredentialKey;
use AgoraServer\Domain\RestfulAPI\AuthCredentialKeyFactory;
use AgoraServer\Domain\UserId;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Class AcquireApi
 * @package AgoraServer\Infrastructure\AgoraRecordingAPIClient
 */
class AcquireApi
{
    private AppId $appId;
    private AuthCredentialKey $authCredentialKey;

    public function __construct(AppIdFactory $appIdFactory,
                                AuthCredentialKeyFactory $authCredentialKeyFactory)
    {
        $this->appId = $appIdFactory->create();
        $this->authCredentialKey = $authCredentialKeyFactory->create();
    }

    public function __invoke(ChannelName $channelName, UserId $userId): ResourceId
    {
        $client = new Client();
        $request = new Request(
            'POST',
            sprintf('https://api.agora.io/v1/apps/%s/cloud_recording/acquire', $this->appId->value()),
            [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Basic ' . $this->authCredentialKey->value()
            ],
            json_encode([
                'cname' => $channelName->value(),
                'uid' => (string) $userId->value(),
                'clientRequest' => [
                    'resourceExpiredHour' => 24,
                ],
            ], JSON_UNESCAPED_UNICODE));

        $response = $client->send($request);
        $responseJson = json_decode((string) $response->getBody(), true);

        return new ResourceId($responseJson['resourceId']);
    }

}