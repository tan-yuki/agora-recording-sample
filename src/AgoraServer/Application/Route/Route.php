<?php
declare(strict_types=1);

namespace AgoraServer\Application\Route;

use AgoraServer\Application\Controller\SecureToken\GetSecureTokenController;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class Route
{
    private App $app;

    private GetSecureTokenController $getSecureTokenController;

    public function __construct(App $app,
                                GetSecureTokenController $getSecureTokenController) {
        $this->app = $app;
        $this->getSecureTokenController = $getSecureTokenController;
    }

    public function bind(): void {
        $this->app->get('/token', function(Request $request, Response $response) {
            return $this->getSecureTokenController->execute($request, $response);
        });
    }

}