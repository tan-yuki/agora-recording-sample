<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Recording;


class AwsCredentials
{
    private string $accessToken;
    private string $secretToken;

    public function __construct(string $accessToken, string $secretToken)
    {
        $this->accessToken = $accessToken;
        $this->secretToken = $secretToken;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getSecretToken(): string
    {
        return $this->secretToken;
    }
}