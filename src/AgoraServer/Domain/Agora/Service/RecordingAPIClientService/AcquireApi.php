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