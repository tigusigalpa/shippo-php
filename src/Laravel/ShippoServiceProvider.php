<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Laravel;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;

class ShippoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shippo.php',
            'shippo'
        );

        $this->app->singleton(Config::class, function ($app) {
            $config = $app['config']['shippo'];

            return Config::make(
                apiToken: $config['api_token'] ?? '',
                options: [
                    'api_version' => $config['api_version'] ?? '2018-02-08',
                    'base_url' => $config['base_url'] ?? 'https://api.goshippo.com',
                    'is_test' => $config['is_test'] ?? false,
                    'timeout' => $config['timeout'] ?? 30,
                    'retry_attempts' => $config['retry_attempts'] ?? 3,
                    'retry_delay' => $config['retry_delay'] ?? 1000,
                ]
            );
        });

        $this->app->singleton(Shippo::class, function ($app) {
            $httpFactory = new HttpFactory();

            return new Shippo(
                httpClient: $app->make(ClientInterface::class, ['config' => [
                    'timeout' => $app->make(Config::class)->timeout,
                ]]),
                requestFactory: $app->make(RequestFactoryInterface::class),
                streamFactory: $app->make(StreamFactoryInterface::class),
                config: $app->make(Config::class)
            );
        });

        $this->app->bind(ClientInterface::class, function ($app, $params) {
            return new GuzzleClient($params['config'] ?? []);
        });

        $this->app->bind(RequestFactoryInterface::class, function () {
            return new HttpFactory();
        });

        $this->app->bind(StreamFactoryInterface::class, function () {
            return new HttpFactory();
        });

        $this->app->alias(Shippo::class, 'shippo');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/shippo.php' => config_path('shippo.php'),
            ], 'shippo-config');
        }
    }

    public function provides(): array
    {
        return [
            Shippo::class,
            Config::class,
            'shippo',
        ];
    }
}
