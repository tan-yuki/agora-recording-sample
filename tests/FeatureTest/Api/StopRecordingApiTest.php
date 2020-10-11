<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingRequest;
use AgoraServer\Application\Controller\Recording\StopRecording\StopRecordingUseCase;
use AgoraServer\Domain\Agora\Entity\Recording\UploadFile;
use AgoraServer\Domain\Agora\Entity\Recording\UploadingStatus;
use AgoraServer\Domain\Agora\Entity\UserId;
use FeatureTest\FeatureBaseTestCase;

class StopRecordingApiTest extends FeatureBaseTestCase
{
    private static array $returnUseCaseValue;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$returnUseCaseValue= [
            UploadingStatus::UPLOADED(),
            [
                new UploadFile("aaa.mp4", new UserId(1), 1),
                new UploadFile("bbb.mp4", new UserId(2), 2),
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            StopRecordingUseCase::class => function() {
                $mock = $this->createMock(StopRecordingUseCase::class);
                $mock->method('__invoke')
                    ->willReturn(self::$returnUseCaseValue);

                return $mock;
            },
        ]);
    }

    /**
     * @test
     */
    public function return_secure_token()
    {
        $response = $this->runApp('POST', '/api/v1/recording/stop', json_encode([
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