<?php
declare(strict_types=1);

namespace FeatureTest;

use AgoraServer\Application\Initializer;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ServerRequestFactory;

class FeatureBaseTestCase extends TestCase
{
    const URL_DOMAIN = 'http://test.example.com';

    private Initializer $initializer;
    protected ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        parent::setup();

        $this->initializer = new Initializer(new ContainerBuilder());
        $this->containerBuilder = $this->initializer->getContainerBuilder();
    }

    protected function runApp(string $method, string $path, string $bodyContents = ''): ResponseInterface
    {
        $request = (new ServerRequestFactory())->createServerRequest($method, self::URL_DOMAIN . $path);

        if (!empty($bodyContents)) {
            $request->getBody()->write($bodyContents);
        }

        return $this->initializer->createApplication()->handle($request);
    }

    /**
     * Convert ResponseInterface to json object array
     * @param ResponseInterface $response
     * @return array
     */
    protected function toArrayResponse(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }


}