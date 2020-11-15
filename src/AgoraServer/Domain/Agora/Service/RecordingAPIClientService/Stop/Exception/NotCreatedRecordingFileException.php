<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception;


class NotCreatedRecordingFileException extends \RuntimeException
{
    const AGORA_RESPONSE_CODE = 435;
}