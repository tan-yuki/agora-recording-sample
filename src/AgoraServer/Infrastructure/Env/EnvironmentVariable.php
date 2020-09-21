<?php
declare(strict_types=1);

namespace AgoraServer\Infrastructure\Env;

use AgoraServer\Infrastructure\Env\Exception\EnvironmentKeyNotFoundException;

final class EnvironmentVariable
{
    const KEY_APP_CERTIFICATE = 'AGORA_APP_CERTIFICATE';

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getAppCertificate(): string
    {
        return $this->getEnvValue(self::KEY_APP_CERTIFICATE);
    }

    /**
     * @param string $key
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    private function getEnvValue(string $key): string
    {
        $value = getenv($key);
        if (empty($value)) {
            throw new EnvironmentKeyNotFoundException($key);
        }

        return $value;
    }


}