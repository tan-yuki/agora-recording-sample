<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService;

use AgoraServer\Domain\Agora\Entity\Project\AppId;
use AgoraServer\Domain\Agora\Entity\Project\AppIdFactory;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKey;
use AgoraServer\Domain\Agora\Entity\RestfulAPI\AuthCredentialKeyFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;

class AgoraRecordingAPIClient
{
    private AppId $appId;
    private AuthCredentialKey $authCredentialKey;
    private Client $client;

    public function __construct(AppIdFactory $appIdFactory,
                                AuthCredentialKeyFactory $authCredentialKeyFactory,
                                Client $client)
    {
        $this->appId = $appIdFactory->create();
        $this->authCredentialKey = $authCredentialKeyFactory->create();
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array  $body
     * @return array
     * @throws GuzzleException
     */
    public function callAgoraApi(string $path, array $body = []): array
    {
        $request = new Request(
            'POST',
            sprintf('https://api.agora.io/v1/apps/%s/cloud_recording%s', $this->appId->value(), $path),
            [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Basic ' . $this->authCredentialKey->value()
            ],
            json_encode($body, JSON_UNESCAPED_UNICODE));

        $response = $this->client->send($request);

        return json_decode((string) $response->getBody(), true);
    }

}