<?php
declare(strict_types=1);

namespace AgoraServer\Application\Route;

use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingController;
use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingController;
use AgoraServer\Application\Controller\SecureToken\GetSecureToken\GetSecureTokenController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

final class Route
{
    private App $app;

    private GetSecureTokenController $getSecureTokenController;
    private StartRecordingController $startRecordingController;
    private StopRecordingController $stopRecordingController;

    public function __construct(App $app,
                                GetSecureTokenController $getSecureTokenController,
                                StartRecordingController $startRecordingController,
                                StopRecordingController $stopRecordingController) {
        $this->app = $app;
        $this->getSecureTokenController = $getSecureTokenController;
        $this->startRecordingController = $startRecordingController;
        $this->stopRecordingController = $stopRecordingController;
    }

    public function bind(): void {
        $this->app->group('/api', function(RouteCollectorProxy $group) {
            $group->group('/v1', function(RouteCollectorProxy $group) {
                $group->get('/token', function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $this->getSecureTokenController->execute($request, $response);
                });
                $group->post('/recording/start', function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $this->startRecordingController->execute($request, $response);
                });
                $group->post('/recording/stop', function(ServerRequestInterface $request, ResponseInterface $response) {
                    return $this->stopRecordingController->execute($request, $response);
                });
            });
        });
    }

}