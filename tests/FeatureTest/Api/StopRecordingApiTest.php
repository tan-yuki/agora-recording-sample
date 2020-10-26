<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingRequest;
use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingUseCase;
use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Entity\UserId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\StopApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Stop\StopApiResponse;
use FeatureTest\FeatureBaseTestCase;

class StopRecordingApiTest extends FeatureBaseTestCase
{
    private static StopApiResponse $stopApiResponse;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$stopApiResponse = new StopApiResponse([
            'uploadingStatus' => 'uploaded',
            'fileList' => [
                [
                    'filename' => 'aaa.mp4',
                    'uid' => 1,
                    'sliceStartTime' => 1,
                ],
                [
                    'filename' => 'bbb.mp4',
                    'uid' => 2,
                    'sliceStartTime' => 2,
                ],
            ],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            StopApi::class => function() {
                $mock = $this->createMock(StopApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$stopApiResponse);

                return $mock;
            },
        ]);
    }

    /**
     * @test
     */
    public function return_secure_token()
    {
        $response = $this->runApp('POST', '/v1/recording/stop', json_encode([
            StopRecordingRequest::PARAM_RESOURCE_ID => 'sample_resource_id',
            StopRecordingRequest::PARAM_SID => 'sample_sid',
            StopRecordingRequest::PARAM_USER_ID => '1234',
            StopRecordingRequest::PARAM_CHANNEL_NAME=> 'channel',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('status', $responseArray);
        $this->assertArrayHasKey('files', $responseArray);

        $this->assertSame(UploadingStatus::UPLOADED()->getValue(), $responseArray['status']);
        $this->assertSame([
            ['fileName' => 'aaa.mp4'],
            ['fileName' => 'bbb.mp4'],
        ], $responseArray['files']);
    }


}