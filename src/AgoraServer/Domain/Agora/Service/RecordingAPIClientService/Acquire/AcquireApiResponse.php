<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire;


use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;

final class AcquireApiResponse
{
    private ResourceId $resourceId;

    public function __construct(array $responseJson)
    {
        $this->resourceId = new ResourceId($responseJson['resourceId']);
    }

    public function getResourceId(): ResourceId
    {
        return $this->resourceId;
    }

}