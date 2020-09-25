<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StartRecording;


use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\AcquireApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\StartApi;
use AgoraServer\Domain\Agora\Entity\UserId;

/**
 * Class StartRecordingUseCase
 * @see https://docs.agora.io/en/cloud-recording/cloud_recording_rest?platform=All%20Platforms#implement-cloud-recording
 * @package AgoraServer\Application\Controller\Recording
 */
class StartRecordingUseCase
{
    private AcquireApi $acquireApi;
    private StartApi $startApi;

    public function __construct(AcquireApi $acquireApi,
                                StartApi $startApi)
    {
        $this->acquireApi = $acquireApi;
        $this->startApi = $startApi;
    }

    public function __invoke(ChannelName $channelName, UserId $userId): RecordingId
    {
        $acquireApi = $this->acquireApi;
        $resourceId = $acquireApi($channelName, $userId);

        $startApi = $this->startApi;
        return $startApi($resourceId, $channelName, $userId);
    }

}