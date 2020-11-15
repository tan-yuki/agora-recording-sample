<?php
declare(strict_types=1);

namespace FeatureTest;

use AgoraServer\Application\Config;
use AgoraServer\Application\Initializer;
use AgoraServer\Infrastructure\Env\EnvironmentName;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use DI\DependencyException;
use DI\NotFoundException;

class FeatureBaseTestCase extends TestCase
{
    const URL_DOMAIN = 'http://test.example.com';

    private Initializer $initializer;
    protected ContainerBuilder $containerBuilder;

    protected function setUp(): void
    {
        parent::setup();

        $builder = new ContainerBuilder();

        $this->initializer = new Initializer(
            new EnvironmentName('DEV'),
            $builder,
            new Config());
        $this->containerBuilder = $builder;
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $bodyContents
     * @return ResponseInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
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