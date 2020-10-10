<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService;

use AgoraServer\Domain\Agora\Entity\Project\AppId;
use AgoraServer\Domain\Agora\Entity\Project\AppIdFactory;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKey;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKeyFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class AgoraRecordingAPIClient
{
    private AppId $appId;
    private AuthCredentialKey $authCredentialKey;

    public function __construct(AppIdFactory $appIdFactory,
                                AuthCredentialKeyFactory $authCredentialKeyFactory)
    {
        $this->appId = $appIdFactory->create();
        $this->authCredentialKey = $authCredentialKeyFactory->create();
    }

    /**
     * @param string $path
     * @param array  $body
     * @return array
     * @throws GuzzleException
     */
    public function callAgoraApi(string $path, array $body = []): array
    {
        $client = new Client();
        $request = new Request(
            'POST',
            sprintf('https://api.agora.io/v1/apps/%s/cloud_recording%s', $this->appId->value(), $path),
            [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Basic ' . $this->authCredentialKey->value()
            ],
            json_encode($body, JSON_UNESCAPED_UNICODE));

        $response = $client->send($request);

        return json_decode((string) $response->getBody(), true);
    }

}