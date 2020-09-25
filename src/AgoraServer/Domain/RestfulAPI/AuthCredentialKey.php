<?php
declare(strict_types=1);


namespace AgoraServer\Domain\RestfulAPI;


/**
 * Class AuthCredentialKey
 *
 * AgoraのRestfulAPIを利用するときに必要となる認証キー
 *
 * @see https://docs.agora.io/en/faq/restful_authentication
 * @package AgoraServer\Domain\Agora
 */
class AuthCredentialKey
{
    private string $authKey;

    public function __construct(CustomerId $customerId, CustomerSecret $customerSecret)
    {
        $this->authKey = base64_encode(sprintf('%s:%s',
            $customerId->value(),
            $customerSecret->value()));
    }

    public function value(): string
    {
        return $this->authKey;
    }
}