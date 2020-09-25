<?php
declare(strict_types=1);

namespace AgoraServer\Application\Route;

use AgoraServer\Application\Controller\Recording\StartRecordingController;
use AgoraServer\Application\Controller\SecureToken\GetSecureTokenController;
use Slim\App;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

final class Route
{
    private App $app;

    private GetSecureTokenController $getSecureTokenController;
    private StartRecordingController $startRecordingController;

    public function __construct(App $app,
                                GetSecureTokenController $getSecureTokenController,
                                StartRecordingController $startRecordingController) {
        $this->app = $app;
        $this->getSecureTokenController = $getSecureTokenController;
        $this->startRecordingController = $startRecordingController;
    }

    public function bind(): void {
        $this->app->get('/token', function(Request $request, Response $response) {
            return $this->getSecureTokenController->execute($request, $response);
        });
        $this->app->post('/recording/start', function(Request $request, Response $response) {
            return $this->startRecordingController->execute($request, $response);
        });
    }

}