<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;


use AgoraServer\Domain\Agora\Entity\UserId;

final class UploadFile
{
    private string $filename;
    private UserId $userId;
    private int $sliceStartTime;

    public function __construct(string $filename,
                                UserId $userId,
                                int $sliceStartTime)
    {
        $this->filename = $filename;
        $this->userId = $userId;
        $this->sliceStartTime = $sliceStartTime;
    }

    public function getFileName(): string
    {
        return $this->filename;
    }

}