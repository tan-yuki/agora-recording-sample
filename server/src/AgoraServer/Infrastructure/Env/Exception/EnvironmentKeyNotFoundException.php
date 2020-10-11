<?php
declare(strict_types=1);

namespace AgoraServer\Infrastructure\Env\Exception;

use Throwable;
use Exception;

class EnvironmentKeyNotFoundException extends Exception
{
    public function __construct(string $missingKeyName, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Missing environment key: %s', $missingKeyName), $code, $previous);
    }
}