<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingRequest;
use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\NotCreatedRecordingFileException;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\Exception\UnknownRecordingStopApiException;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\StopApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\StopApiResponse;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use FeatureTest\FeatureBaseTestCase;

class StopRecordingApiTest extends FeatureBaseTestCase
{
    private static StopApiResponse $stopApiResponse;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$stopApiResponse = new StopApiResponse(
            UploadingStatus::UPLOADED(),
            new UploadFile('aaa.m3u8'),
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockAsNormalScenarios();
    }

    private function mockAsNormalScenarios(): void
    {
        $this->containerBuilder->addDefinitions([
            StopApi::class => function () {
                $mock = $this->createMock(StopApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$stopApiResponse);

                return $mock;
            },
        ]);
    }

    private function mockAsExceptionScenarios(Exception $e): void
    {
        $this->containerBuilder->addDefinitions([
            StopApi::class => function () use ($e) {
                $mock = $this->createMock(StopApi::class);
                $mock->method('__invoke')
                    ->willThrowException($e);

                return $mock;
            },
        ]);

    }

    /**
     * @test
     */
    public function return_200_and_response()
    {
        $response = $this->runApp('POST', '/v1/recording/stop', json_encode([
            StopRecordingRequest::PARAM_RESOURCE_ID  => 'sample_resource_id',
            StopRecordingRequest::PARAM_SID          => 'sample_sid',
            StopRecordingRequest::PARAM_USER_ID      => '1234',
            StopRecordingRequest::PARAM_CHANNEL_NAME => 'channel',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('status', $responseArray);
        $this->assertArrayHasKey('file', $responseArray);

        $this->assertSame(UploadingStatus::UPLOADED()->getValue(), $responseArray['status']);
        $this->assertSame('aaa.m3u8', $responseArray['file']);
    }


    public function apiErrorDataProvider(): array
    {
        return [
            'not create file error' => [NotCreatedRecordingFileException::class, 400],
            'unknown error'         => [UnknownRecordingStopApiException::class, 500],
        ];
    }

    /**
     * @test
     * @dataProvider apiErrorDataProvider
     * @param string $exceptionClass
     * @param int    $errorCode
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function return_error(string $exceptionClass, int $errorCode): void
    {
        $message = 'this is sample error message';
        $e = new $exceptionClass($message);

        $this->mockAsExceptionScenarios($e);

        $response = $this->runApp('POST', '/v1/recording/stop', json_encode([
            StopRecordingRequest::PARAM_RESOURCE_ID  => 'sample_resource_id',
            StopRecordingRequest::PARAM_SID          => 'sample_sid',
            StopRecordingRequest::PARAM_USER_ID      => '1234',
            StopRecordingRequest::PARAM_CHANNEL_NAME => 'channel',
        ]));

        $this->assertSame($errorCode, $response->getStatusCode());
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('message', $responseArray);

        $this->assertSame($message, $responseArray['message']);
    }
}