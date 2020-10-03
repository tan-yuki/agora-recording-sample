<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingRequest;
use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingUseCase;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use FeatureTest\FeatureBaseTestCase;

class StartRecordingApiTest extends FeatureBaseTestCase
{
    /**
     * Mockから返されるRecordingId
     *
     * @var RecordingId
     */
    private static RecordingId $returnRecordingId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$returnRecordingId = new RecordingId('this_is_recording_id');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            StartRecordingUseCase::class => function() {
                $mock = $this->createMock(StartRecordingUseCase::class);
                $mock->method('__invoke')
                    ->willReturn(self::$returnRecordingId);

                return $mock;
            },
        ]);
    }

    /**
     * @test
     */
    public function return_secure_token()
    {
        $response = $this->runApp('POST', '/recording/start', json_encode([
            StartRecordingRequest::PARAM_USER_ID => '1234',
            StartRecordingRequest::PARAM_CHANNEL_NAME=> 'channel',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('recordingId', $responseArray);
        $this->assertSame(self::$returnRecordingId->value(), $responseArray['recordingId']);
    }


}