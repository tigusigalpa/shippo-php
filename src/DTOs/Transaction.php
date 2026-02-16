<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

use Tigusigalpa\Shippo\Enums\ObjectState;

final readonly class Transaction
{
    public function __construct(
        public string $objectId,
        public ObjectState $objectState,
        public string $objectCreated,
        public string $objectUpdated,
        public string $objectOwner,
        public ?string $status = null,
        public ?string $rate = null,
        public ?string $trackingNumber = null,
        public ?string $trackingStatus = null,
        public ?string $trackingUrlProvider = null,
        public ?string $eta = null,
        public ?string $labelUrl = null,
        public ?string $commercialInvoiceUrl = null,
        public ?array $metadata = null,
        public ?array $messages = null,
        public ?bool $test = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            objectId: $data['object_id'],
            objectState: ObjectState::from($data['object_state']),
            objectCreated: $data['object_created'],
            objectUpdated: $data['object_updated'],
            objectOwner: $data['object_owner'],
            status: $data['status'] ?? null,
            rate: $data['rate'] ?? null,
            trackingNumber: $data['tracking_number'] ?? null,
            trackingStatus: $data['tracking_status'] ?? null,
            trackingUrlProvider: $data['tracking_url_provider'] ?? null,
            eta: $data['eta'] ?? null,
            labelUrl: $data['label_url'] ?? null,
            commercialInvoiceUrl: $data['commercial_invoice_url'] ?? null,
            metadata: $data['metadata'] ?? null,
            messages: $data['messages'] ?? null,
            test: $data['test'] ?? null,
        );
    }
}
