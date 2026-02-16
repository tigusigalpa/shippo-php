<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

use Tigusigalpa\Shippo\Enums\ObjectState;

final readonly class Shipment
{
    public function __construct(
        public string $objectId,
        public ObjectState $objectState,
        public string $objectCreated,
        public string $objectUpdated,
        public string $objectOwner,
        public ?string $addressFrom = null,
        public ?string $addressTo = null,
        public ?string $addressReturn = null,
        public ?array $parcels = null,
        public ?array $rates = null,
        public ?string $carrierAccounts = null,
        public ?array $metadata = null,
        public ?array $extra = null,
        public ?string $customsDeclaration = null,
        public ?bool $test = null,
        public ?array $messages = null,
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
            addressFrom: $data['address_from'] ?? null,
            addressTo: $data['address_to'] ?? null,
            addressReturn: $data['address_return'] ?? null,
            parcels: $data['parcels'] ?? null,
            rates: $data['rates'] ?? null,
            carrierAccounts: $data['carrier_accounts'] ?? null,
            metadata: $data['metadata'] ?? null,
            extra: $data['extra'] ?? null,
            customsDeclaration: $data['customs_declaration'] ?? null,
            test: $data['test'] ?? null,
            messages: $data['messages'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'object_id' => $this->objectId,
            'object_state' => $this->objectState->value,
            'address_from' => $this->addressFrom,
            'address_to' => $this->addressTo,
            'address_return' => $this->addressReturn,
            'parcels' => $this->parcels,
            'rates' => $this->rates,
            'metadata' => $this->metadata,
            'extra' => $this->extra,
            'customs_declaration' => $this->customsDeclaration,
        ], fn($value) => $value !== null);
    }
}
