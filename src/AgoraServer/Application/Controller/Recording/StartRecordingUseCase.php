<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording;


use AgoraServer\Domain\ChannelName;
use AgoraServer\Domain\Recording\RecordingId;
use AgoraServer\Domain\Service\AgoraRecordingAPIClient\AcquireApi;
use AgoraServer\Domain\Service\AgoraRecordingAPIClient\StartApi;
use AgoraServer\Domain\UserId;

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