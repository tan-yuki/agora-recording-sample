<?php
declare(strict_types=1);


namespace AgoraServer\Infrastructure\Env;


use Monolog\Logger;
use MyCLabs\Enum\Enum;

/**
 * Environment name.
 * Class EnvironmentName
 * @package AgoraServer\Infrastructure\Env
 */
final class EnvironmentName extends Enum
{
    const DEV = "DEV";
    const PRODUCTION = "PRODUCTION";

    public function getLoggerLevel(): int
    {
        switch($this->getValue()) {
            case self::PRODUCTION:
                return Logger::NOTICE;
            default:
                return Logger::DEBUG;
        }
    }
}