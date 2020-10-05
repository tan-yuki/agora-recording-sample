<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StopRecording;


use AgoraServer\Application\Shared\CreateRequestExceptionFromValidationErrorTrait;
use AgoraServer\Application\Shared\RequestUriQueryToArrayTrait;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Valitron\Validator;

class StopRecordingRequest
{
    use RequestUriQueryToArrayTrait;
    use CreateRequestExceptionFromValidationErrorTrait;

    const PARAM_CHANNEL_NAME = 'channelName';
    const PARAM_USER_ID = 'userId';
    const PARAM_RESOURCE_ID = 'resourceId';
    const PARAM_SID = 'sid';

    /**
     * @param ServerRequestInterface $request
     * @return array
     * @throws HttpBadRequestException
     */
    public function validate(ServerRequestInterface $request): array
    {
        $requestJson = json_decode((string) $request->getBody(), true);
        $v = new Validator($requestJson);

        $v->rule('required', [
            self::PARAM_CHANNEL_NAME,
            self::PARAM_USER_ID,
            self::PARAM_RESOURCE_ID,
            self::PARAM_SID,
        ]);

        $v->rule('numeric', self::PARAM_USER_ID);

        if (!$v->validate()) {
            throw $this->createRequestException($request, $v->errors());
        }

        return [
            self::PARAM_CHANNEL_NAME => $requestJson[self::PARAM_CHANNEL_NAME],
            self::PARAM_USER_ID      => (int) $requestJson[self::PARAM_USER_ID],
            self::PARAM_SID          => $requestJson[self::PARAM_SID],
            self::PARAM_RESOURCE_ID  => $requestJson[self::PARAM_RESOURCE_ID],
        ];
    }

}