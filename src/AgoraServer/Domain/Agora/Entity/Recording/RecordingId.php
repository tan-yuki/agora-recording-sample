<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;


class RecordingId
{
    private string $recordingId;
    public function __construct(string $recordingId)
    {
        $this->recordingId = $recordingId;
    }

    public function value(): string
    {
        return $this->recordingId;
    }

}