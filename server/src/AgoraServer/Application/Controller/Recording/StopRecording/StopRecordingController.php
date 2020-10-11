<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\Recording\StopRecording;

use AgoraServer\Application\Controller\Base\ControllerInterface;
use AgoraServer\Application\Controller\SecureToken\GetSecureToken\GetSecureTokenRequest;
use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Entity\UserId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;


final class StopRecordingController implements ControllerInterface
{
    use ResponseWithJsonTrait;

    private StopRecordingRequest $request;
    private StopRecordingUseCase $useCase;

    public function __construct(StopRecordingRequest $request,
                                StopRecordingUseCase $useCase)
    {
        $this->request = $request;
        $this->useCase = $useCase;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return ResponseInterface
     * @throws HttpBadRequestException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $validParams = $this->request->validate($request);
        $useCase = $this->useCase;

        /** @var UploadingStatus $uploadingStatus */
        /** @var UploadFile[] $uploadFiles*/
        list($uploadingStatus, $uploadFiles) = $useCase(
            new ResourceId($validParams[StopRecordingRequest::PARAM_RESOURCE_ID]),
            new RecordingId($validParams[StopRecordingRequest::PARAM_SID]),
            new ChannelName($validParams[GetSecureTokenRequest::PARAM_CHANNEL_NAME]),
            new UserId($validParams[GetSecureTokenRequest::PARAM_USER_ID]),
        );

        return $this->withJson($response, [
            'status' => $uploadingStatus->getValue(),
            'files' => array_map(function(UploadFile $f) {
                return [
                    'fileName' => $f->getFileName(),
                ];
            }, $uploadFiles),
        ]);
    }

}