<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop;


use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\NotCreatedRecordingFileException;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\UnknownRecordingStopApiException;
use Psr\Log\LoggerInterface;

final class StopApiResponseFactory
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @param array $responseJson
     * @return StopApiResponse
     */
    public function createResponse(array $responseJson): StopApiResponse
    {
        $serverResponse = $responseJson['serverResponse'] ?? null;

        if (is_null($serverResponse)) {
            $code = $responseJson['code'] ?? null;

            if ($code === NotCreatedRecordingFileException::AGORA_RESPONSE_CODE) {
                throw new NotCreatedRecordingFileException(
                    'Not created recording file because no user is sending any stream in the channel.'
                );
            }

            $this->logger->error('Stop api error. response: ' . print_r($responseJson, true));

            throw new UnknownRecordingStopApiException('Unknown error occurred in stop api');
        }

        return new StopApiResponse(
            new UploadingStatus($serverResponse['uploadingStatus']),
            new UploadFile($serverResponse['fileList']),
        );
    }
}