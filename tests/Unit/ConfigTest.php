<?php

declare(strict_types=1);

use Tigusigalpa\Shippo\Config;

test('config can be created with minimal parameters', function () {
    $config = new Config('test_token');

    expect($config->apiToken)->toBe('test_token')
        ->and($config->apiVersion)->toBe('2018-02-08')
        ->and($config->baseUrl)->toBe('https://api.goshippo.com')
        ->and($config->isTest)->toBeFalse()
        ->and($config->timeout)->toBe(30)
        ->and($config->retryAttempts)->toBe(3)
        ->and($config->retryDelay)->toBe(1000);
});

test('config can be created with custom options', function () {
    $config = Config::make('custom_token', [
        'api_version' => '2019-01-01',
        'base_url' => 'https://custom.api.com',
        'is_test' => true,
        'timeout' => 60,
        'retry_attempts' => 5,
        'retry_delay' => 2000,
    ]);

    expect($config->apiToken)->toBe('custom_token')
        ->and($config->apiVersion)->toBe('2019-01-01')
        ->and($config->baseUrl)->toBe('https://custom.api.com')
        ->and($config->isTest)->toBeTrue()
        ->and($config->timeout)->toBe(60)
        ->and($config->retryAttempts)->toBe(5)
        ->and($config->retryDelay)->toBe(2000);
});

test('config generates correct headers', function () {
    $config = new Config('test_token_123', '2020-01-01');

    $headers = $config->getHeaders();

    expect($headers)->toHaveKey('Authorization')
        ->and($headers['Authorization'])->toBe('ShippoToken test_token_123')
        ->and($headers)->toHaveKey('Shippo-API-Version')
        ->and($headers['Shippo-API-Version'])->toBe('2020-01-01')
        ->and($headers)->toHaveKey('Content-Type')
        ->and($headers['Content-Type'])->toBe('application/json')
        ->and($headers)->toHaveKey('Accept')
        ->and($headers['Accept'])->toBe('application/json');
});
