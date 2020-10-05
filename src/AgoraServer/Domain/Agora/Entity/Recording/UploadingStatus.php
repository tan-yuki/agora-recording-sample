<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Recording;

use MyCLabs\Enum\Enum;

/**
 * Class UploadingStatus
 * @package AgoraServer\Domain\Agora\Entity\Recording
 *
 * @method static UPLOADED()
 * @method static BACKUPED()
 * @method static UNKNOWN()
 */
final class UploadingStatus extends Enum
{
    private const UPLOADED = 'uploaded';
    private const BACKUPED = 'backuped';
    private const UNKNOWN = 'unknown';
}