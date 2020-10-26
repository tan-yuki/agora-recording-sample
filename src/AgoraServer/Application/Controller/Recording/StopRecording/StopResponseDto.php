<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StopRecording;


use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;

final class StopResponseDto
{
    private UploadingStatus $uploadingStatus;

    /**
     * @var UploadFile[]
     */
    private array $uploadFiles;

    /**
     * StopResponseDto constructor.
     * @param UploadingStatus $uploadingStatus
     * @param UploadFile[] $uploadFiles
     */
    public function __construct(UploadingStatus $uploadingStatus,
                                array $uploadFiles)
    {
        $this->uploadingStatus = $uploadingStatus;
        $this->uploadFiles = $uploadFiles;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->uploadingStatus->getValue(),
            'files' => array_map(function(UploadFile $f) {
                return [
                    'fileName' => $f->getFileName(),
                ];
            }, $this->uploadFiles),
        ];
    }

}