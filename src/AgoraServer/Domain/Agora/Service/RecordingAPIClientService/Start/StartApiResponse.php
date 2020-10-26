<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start;


use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;

final class StartApiResponse
{
    private RecordingId $recordingId;

    public function __construct(array $responseJson)
    {
        $this->recordingId = new RecordingId($responseJson['sid']);
    }

    public function getRecordingId(): RecordingId
    {
        return $this->recordingId;
    }

}