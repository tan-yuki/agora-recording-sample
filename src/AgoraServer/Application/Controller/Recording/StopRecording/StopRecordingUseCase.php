<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StopRecording;


use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire\AcquireApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start\StartApi;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\StopApi;

/**
 * Class StartRecordingUseCase
 * @see https://docs.agora.io/en/cloud-recording/cloud_recording_rest?platform=All%20Platforms#implement-cloud-recording
 * @package AgoraServer\Application\Controller\Recording
 */
class StopRecordingUseCase
{
    private StopApi $stopApi;

    public function __construct(StopApi $stopApi)
    {
        $this->stopApi = $stopApi;
    }

    public function __invoke(ResourceId $resourceId,
                             RecordingId $recordingId,
                             ChannelName $channelName,
                             UserId $userId): StopResponseDto
    {
        $stopApi = $this->stopApi;
        $stopApiResponse = $stopApi($resourceId, $recordingId, $channelName, $userId);

        return new StopResponseDto(
            $stopApiResponse->getUploadingStatus(),
            $stopApiResponse->getUploadFiles());
    }

}