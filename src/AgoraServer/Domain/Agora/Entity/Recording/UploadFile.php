<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;

final class UploadFile
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getFileName(): string
    {
        return $this->filename;
    }

}