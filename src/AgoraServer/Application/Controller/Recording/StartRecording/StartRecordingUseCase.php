<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StartRecording;


use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Project\SecureToken;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire\AcquireApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start\StartApi;
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

    public function __invoke(ChannelName $channelName,
                             UserId $userId,
                             SecureToken $token): StartResponseDto
    {
        // Get resource id from acquire api
        $acquireApi = $this->acquireApi;
        $acquireApiResponse = $acquireApi($channelName, $userId);
        $resourceId = $acquireApiResponse->getResourceId();

        // Get recording id from start api
        $startApi = $this->startApi;
        $startApiResponse = $startApi($resourceId, $channelName, $userId, $token);

        return new StartResponseDto($resourceId, $startApiResponse->getRecordingId());
    }

}