<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop;


use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;

final class StopApiResponse
{
    private UploadingStatus $uploadingStatus;

    private UploadFile $uploadingFile;

    /**
     * StopApiResponse constructor.
     * @param array $responseJson
     */
    public function __construct(array $responseJson)
    {
        $serverResponse = $responseJson['serverResponse'];
        $this->uploadingStatus = new UploadingStatus($serverResponse['uploadingStatus']);
        $this->uploadingFile = new UploadFile($serverResponse['fileList']);
    }

    public function getUploadingStatus(): UploadingStatus
    {
        return $this->uploadingStatus;
    }

    public function getUploadFile(): UploadFile
    {
        return $this->uploadingFile;
    }

}