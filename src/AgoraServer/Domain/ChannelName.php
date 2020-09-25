<?php
declare(strict_types=1);


namespace AgoraServer\Domain;


final class ChannelName
{
    private string $channelName;
    public function __construct(string $channelName)
    {
        // TODO: validate string characters
        // https://docs.agora.io/en/Agora%20Platform/token/#app-certificate
        $this->channelName = $channelName;
    }

    public function value(): string
    {
        return $this->channelName;
    }
}