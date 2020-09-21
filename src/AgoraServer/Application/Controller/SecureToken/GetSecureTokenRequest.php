<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\SecureToken;

use AgoraServer\Application\Shared\CreateRequestExceptionFromValidationErrorTrait;
use AgoraServer\Application\Shared\RequestUriQueryToArrayTrait;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Valitron\Validator;

final class GetSecureTokenRequest
{
    use RequestUriQueryToArrayTrait;
    use CreateRequestExceptionFromValidationErrorTrait;

    const PARAM_APP_ID = 'appId';
    const PARAM_CHANNEL_NAME = 'channelName';
    const PARAM_USER_ID = 'userId';

    /**
     * @param ServerRequestInterface $request
     * @return array
     * @throws HttpBadRequestException
     */
    public function validate(ServerRequestInterface $request): array
    {
        $params = $this->toArrayFromURI($request->getUri());
        $v = new Validator($params);

        $v->rule('required', [
            self::PARAM_APP_ID,
            self::PARAM_CHANNEL_NAME,
            self::PARAM_USER_ID
        ]);

        $v->rule('numeric', self::PARAM_USER_ID);

        if (!$v->validate()) {
            $exception = new HttpBadRequestException($request);
            $exception->setDescription(join($v->errors(), ','));

            throw $this->createRequestException($request, $v->errors());
        }

        return [
            self::PARAM_APP_ID       => $params[self::PARAM_APP_ID],
            self::PARAM_CHANNEL_NAME => $params[self::PARAM_CHANNEL_NAME],
            self::PARAM_USER_ID      => (int) $params[self::PARAM_USER_ID],
        ];
    }
}