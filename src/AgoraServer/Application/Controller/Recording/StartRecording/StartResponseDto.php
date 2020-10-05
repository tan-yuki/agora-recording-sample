<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StartRecording;


use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;

final class StartResponseDto
{
    private ResourceId $resourceId;
    private RecordingId $recordingId;

    public function __construct(ResourceId $resourceId,
                                RecordingId $recordingId)
    {
        $this->resourceId = $resourceId;
        $this->recordingId = $recordingId;
    }

    public function toArray(): array
    {
        return [
            'resourceId' => $this->resourceId->value(),
            'sid' => $this->recordingId->value(),
        ];
    }

}