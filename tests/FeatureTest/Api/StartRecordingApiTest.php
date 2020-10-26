<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingRequest;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\AcquireApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\StartApi;
use FeatureTest\FeatureBaseTestCase;

class StartRecordingApiTest extends FeatureBaseTestCase
{
    private static ResourceId $resourceId;
    private static RecordingId $recordingId;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$resourceId = new ResourceId('this_is_resource_id');
        self::$recordingId = new RecordingId('this_is_recording_id');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            AcquireApi::class => function() {
                $mock = $this->createMock(AcquireApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$resourceId);

                return $mock;
            },
            StartApi::class => function() {
                $mock = $this->createMock(StartApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$recordingId);

                return $mock;
            },
        ]);
    }

    /**
     * @test
     */
    public function return_200_and_response()
    {
        $response = $this->runApp('POST', '/v1/recording/start', json_encode([
            StartRecordingRequest::PARAM_USER_ID => '1234',
            StartRecordingRequest::PARAM_CHANNEL_NAME=> 'channel',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('sid', $responseArray);
        $this->assertArrayHasKey('resourceId', $responseArray);

        $this->assertSame(self::$recordingId->value(), $responseArray['sid']);
        $this->assertSame(self::$resourceId->value(), $responseArray['resourceId']);
    }


}