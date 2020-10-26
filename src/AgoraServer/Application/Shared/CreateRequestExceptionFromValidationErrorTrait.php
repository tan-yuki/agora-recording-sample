<?php
declare(strict_types=1);


namespace AgoraServer\Application\Shared;


use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

trait CreateRequestExceptionFromValidationErrorTrait
{
    /**
     * @param ServerRequestInterface $request
     * @param array                  $errors
     *                                      Valitron errors.
     *                                      This array format is:
     *                                      ```
     *                                      [
     *                                        key1 => [err1, err2],
     *                                        key2 => [err1, err2],
     *                                      ]
     *                                      ```
     *
     * @return HttpBadRequestException
     */
    protected function createRequestException(ServerRequestInterface $request, array $errors): HttpBadRequestException
    {
        $message_list = [];
        foreach ($errors as $errs) {
            foreach ($errs as $e) {
                $message_list[] = $e;
            }
        }
        $exception = new HttpBadRequestException($request);

        return $exception->setDescription(join(', ', $message_list));
    }

}