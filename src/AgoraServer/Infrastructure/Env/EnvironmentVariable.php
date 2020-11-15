<?php
declare(strict_types=1);

namespace AgoraServer\Infrastructure\Env;

use AgoraServer\Infrastructure\Env\Exception\EnvironmentKeyNotFoundException;

final class EnvironmentVariable
{
    const KEY_APP_ID = 'AGORA_APP_ID';
    const KEY_APP_CERTIFICATE = 'AGORA_APP_CERTIFICATE';
    const KEY_HEADER_ACCESS_CONTROL_ALLOW_ORIGIN = 'HEADER_ACCESS_CONTROL_ALLOW_ORIGIN';
    const KEY_RESTFUL_API_CUSTOMER_ID = 'AGORA_RESTFUL_API_CUSTOMER_ID';
    const KEY_RESTFUL_API_CUSTOMER_SECRET = 'AGORA_RESTFUL_API_CUSTOMER_SECRET';
    const KEY_RECORDING_AWS_S3_BUCKET_NAME = 'AGORA_RECORDING_AWS_S3_BUCKET_NAME';
    const KEY_RECORDING_AWS_ACCESS_TOKEN = 'AWS_ACCESS_KEY_ID';
    const KEY_RECORDING_AWS_SECRET_TOKEN = 'AWS_SECRET_ACCESS_KEY';

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getAppId(): string
    {
        return $this->getEnvValue(self::KEY_APP_ID);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getAppCertificate(): string
    {
        return $this->getEnvValue(self::KEY_APP_CERTIFICATE);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getHeaderAccessControlAllowOrigin(): string
    {
        return $this->getEnvValue(self::KEY_HEADER_ACCESS_CONTROL_ALLOW_ORIGIN);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getRestfulAPICustomerId(): string
    {
        return $this->getEnvValue(self::KEY_RESTFUL_API_CUSTOMER_ID);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getRestfulAPICustomerSecret(): string
    {
        return $this->getEnvValue(self::KEY_RESTFUL_API_CUSTOMER_SECRET);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getRecordingAwsAccessToken(): string
    {
        return $this->getEnvValue(self::KEY_RECORDING_AWS_ACCESS_TOKEN);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getRecordingAwsSecretToken(): string
    {
        return $this->getEnvValue(self::KEY_RECORDING_AWS_SECRET_TOKEN);
    }

    /**
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    public function getAWSS3BucketName(): string
    {
        return $this->getEnvValue(self::KEY_RECORDING_AWS_S3_BUCKET_NAME);
    }

    /**
     * @param string $key
     * @return string
     * @throws EnvironmentKeyNotFoundException
     */
    private function getEnvValue(string $key): string
    {
        $value = $_ENV[$key];

        if (empty($value)) {
            throw new EnvironmentKeyNotFoundException($key);
        }

        return $value;
    }
}
