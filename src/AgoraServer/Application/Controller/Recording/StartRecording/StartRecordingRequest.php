<?php
declare(strict_types=1);


namespace AgoraServer\Application\Controller\Recording\StartRecording;


use AgoraServer\Application\Controller\Base\RequestInterface;
use AgoraServer\Application\Shared\CreateRequestExceptionFromValidationErrorTrait;
use AgoraServer\Application\Shared\RequestBodyToArrayTrait;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

final class StartRecordingRequest implements RequestInterface
{
    use CreateRequestExceptionFromValidationErrorTrait;
    use RequestBodyToArrayTrait;

    const PARAM_CHANNEL_NAME = 'channelName';
    const PARAM_USER_ID = 'userId';
    const PARAM_TOKEN = 'token';

    /**
     * @inheritDoc
     */
    public function validate(ServerRequestInterface $request): array
    {
        $requestJson = $this->toArrayFromRequestBody($request);
        $v = new Validator($requestJson);

        $v->rule('required', [
            self::PARAM_CHANNEL_NAME,
            self::PARAM_USER_ID,
            self::PARAM_TOKEN,
        ]);

        $v->rule('numeric', self::PARAM_USER_ID);

        if (!$v->validate()) {
            throw $this->createRequestException($request, $v->errors());
        }

        return [
            self::PARAM_CHANNEL_NAME => $requestJson[self::PARAM_CHANNEL_NAME],
            self::PARAM_USER_ID      => (int) $requestJson[self::PARAM_USER_ID],
            self::PARAM_TOKEN        => $requestJson[self::PARAM_TOKEN],
        ];
    }

}