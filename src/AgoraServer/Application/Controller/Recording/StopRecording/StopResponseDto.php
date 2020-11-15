<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\Recording\StopRecording;

use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;

final class StopResponseDto
{
    private UploadingStatus $uploadingStatus;
    private UploadFile $uploadFile;

    public function __construct(UploadingStatus $uploadingStatus,
                                UploadFile $uploadFile)
    {
        $this->uploadingStatus = $uploadingStatus;
        $this->uploadFile = $uploadFile;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->uploadingStatus->getValue(),
            'file' => $this->uploadFile->getFileName(),
        ];
    }

}