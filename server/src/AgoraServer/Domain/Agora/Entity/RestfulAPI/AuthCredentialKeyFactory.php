<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\RestfulAPI;

class AuthCredentialKeyFactory
{
    private CustomerId $customerId;
    private CustomerSecret $customerSecret;

    public function __construct(CustomerIdFactory $customerIdFactory,
                                CustomerSecretFactory $customerSecretFactory)
    {
        $this->customerId = $customerIdFactory->create();
        $this->customerSecret = $customerSecretFactory->create();
    }

    public function create(): AuthCredentialKey
    {
        return new AuthCredentialKey($this->customerId, $this->customerSecret);
    }

}