<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop;


use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Entity\UserId;

final class StopApiResponse
{
    private UploadingStatus $uploadingStatus;

    /**
     * @var UploadFile[]
     */
    private array $uploadingFiles;

    /**
     * StopApiResponse constructor.
     * @param array $responseJson
     */
    public function __construct(array $responseJson)
    {
        $this->uploadingStatus = new UploadingStatus($responseJson['uploadingStatus']);
        $this->uploadingFiles = array_map(function($file) {
            return new UploadFile(
                $file['filename'],
                new UserId($file['uid']),
                $file['sliceStartTime']
            );
        }, $responseJson['fileList']);
    }

    public function getUploadingStatus(): UploadingStatus
    {
        return $this->uploadingStatus;
    }

    /**
     * @return UploadFile[]
     */
    public function getUploadFiles(): array
    {
        return $this->uploadingFiles;
    }

}