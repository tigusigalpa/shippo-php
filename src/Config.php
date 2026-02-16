<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo;

final readonly class Config
{
    public function __construct(
        public string $apiToken,
        public string $apiVersion = '2018-02-08',
        public string $baseUrl = 'https://api.goshippo.com',
        public bool $isTest = false,
        public int $timeout = 30,
        public int $retryAttempts = 3,
        public int $retryDelay = 1000,
    ) {
    }

    public static function make(string $apiToken, array $options = []): self
    {
        return new self(
            apiToken: $apiToken,
            apiVersion: $options['api_version'] ?? '2018-02-08',
            baseUrl: $options['base_url'] ?? 'https://api.goshippo.com',
            isTest: $options['is_test'] ?? false,
            timeout: $options['timeout'] ?? 30,
            retryAttempts: $options['retry_attempts'] ?? 3,
            retryDelay: $options['retry_delay'] ?? 1000,
        );
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'ShippoToken ' . $this->apiToken,
            'Shippo-API-Version' => $this->apiVersion,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
