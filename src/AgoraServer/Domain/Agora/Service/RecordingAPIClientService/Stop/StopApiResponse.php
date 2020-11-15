<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop;


use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;

final class StopApiResponse
{
    private UploadingStatus $uploadingStatus;
    private UploadFile $uploadingFile;

    public function __construct(UploadingStatus $status, UploadFile $file)
    {
        $this->uploadingStatus = $status;
        $this->uploadingFile = $file;
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