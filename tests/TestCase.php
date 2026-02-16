<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;

abstract class TestCase extends BaseTestCase
{
    protected function createShippoClient(string $apiToken = 'test_token', array $options = []): Shippo
    {
        $config = Config::make($apiToken, $options);
        $httpFactory = new HttpFactory();

        return new Shippo(
            httpClient: new GuzzleClient(['timeout' => $config->timeout]),
            requestFactory: $httpFactory,
            streamFactory: $httpFactory,
            config: $config
        );
    }

    protected function getTestApiToken(): string
    {
        return $_ENV['SHIPPO_TEST_TOKEN'] ?? 'shippo_test_token';
    }
}
