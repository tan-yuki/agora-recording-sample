<?php
declare(strict_types=1);


namespace FeatureTest\Api;

use AgoraServer\Application\Controller\Recording\StartRecording\StartRecordingRequest;
use AgoraServer\Domain\Agora\Entity\Recording\RecordingId;
use AgoraServer\Domain\Agora\Entity\Recording\ResourceId;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire\AcquireApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Acquire\AcquireApiResponse;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start\StartApi;
use AgoraServer\Domain\Agora\Service\RecordingAPIClientService\Start\StartApiResponse;
use FeatureTest\FeatureBaseTestCase;

class StartRecordingApiTest extends FeatureBaseTestCase
{
    private static AcquireApiResponse $acquireApiResponse;
    private static StartApiResponse $startApiResponse;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$acquireApiResponse = new AcquireApiResponse([
            'resourceId' => 'resource_id_sample',
        ]);
        self::$startApiResponse = new StartApiResponse([
            'sid' => 'sid_sample',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->containerBuilder->addDefinitions([
            AcquireApi::class => function() {
                $mock = $this->createMock(AcquireApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$acquireApiResponse);

                return $mock;
            },
            StartApi::class => function() {
                $mock = $this->createMock(StartApi::class);
                $mock->method('__invoke')
                    ->willReturn(self::$startApiResponse);

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
            StartRecordingRequest::PARAM_TOKEN => 'abcdefg',
        ]));

        $this->assertSame(200, $response->getStatusCode(), sprintf('Error message: %s', $response->getBody()));
        $responseArray = $this->toArrayResponse($response);
        $this->assertArrayHasKey('sid', $responseArray);
        $this->assertArrayHasKey('resourceId', $responseArray);

        $this->assertSame(self::$startApiResponse->getRecordingId()->value(), $responseArray['sid']);
        $this->assertSame(self::$acquireApiResponse->getResourceId()->value(), $responseArray['resourceId']);
    }


}