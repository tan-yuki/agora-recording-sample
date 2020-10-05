<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingRequest;
use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingUseCase;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use FeatureTest\FeatureBaseTestCase;

class StartRecordingApiTest extends FeatureBaseTestCase
{
    private static array $returnUseCaseValue;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$returnUseCaseValue= [
            new ResourceId('this_is_resource_id'),
            new RecordingId('this_is_recording_id'),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            StartRecordingUseCase::class => function() {
                $mock = $this->createMock(StartRecordingUseCase::class);
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
        $response = $this->runApp('POST', '/recording/start', json_encode([
            StartRecordingRequest::PARAM_USER_ID => '1234',
            StartRecordingRequest::PARAM_CHANNEL_NAME=> 'channel',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('sid', $responseArray);
        $this->assertArrayHasKey('resourceId', $responseArray);

        $expectsResponse = self::$returnUseCaseValue;
        $this->assertSame($expectsResponse[0]->value(), $responseArray['sid']);
        $this->assertSame($expectsResponse[1]->value(), $responseArray['res']);
    }


}