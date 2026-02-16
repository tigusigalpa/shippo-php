<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

final readonly class Tracking
{
    public function __construct(
        public string $carrier,
        public string $trackingNumber,
        public ?string $addressFrom = null,
        public ?string $addressTo = null,
        public ?string $eta = null,
        public ?string $originalEta = null,
        public ?string $servicelevel = null,
        public ?string $trackingStatus = null,
        public ?array $trackingHistory = null,
        public ?array $metadata = null,
        public ?bool $test = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            carrier: $data['carrier'],
            trackingNumber: $data['tracking_number'],
            addressFrom: $data['address_from'] ?? null,
            addressTo: $data['address_to'] ?? null,
            eta: $data['eta'] ?? null,
            originalEta: $data['original_eta'] ?? null,
            servicelevel: $data['servicelevel'] ?? null,
            trackingStatus: $data['tracking_status'] ?? null,
            trackingHistory: $data['tracking_history'] ?? null,
            metadata: $data['metadata'] ?? null,
            test: $data['test'] ?? null,
        );
    }
}
