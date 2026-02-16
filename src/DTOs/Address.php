<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

use Tigusigalpa\Shippo\Enums\ObjectState;
use Tigusigalpa\Shippo\Enums\ValidationStatus;

final readonly class Address
{
    public function __construct(
        public string $objectId,
        public ObjectState $objectState,
        public ?string $name = null,
        public ?string $company = null,
        public ?string $street1 = null,
        public ?string $street2 = null,
        public ?string $street3 = null,
        public ?string $streetNo = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $country = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?bool $isResidential = null,
        public ?ValidationStatus $validationStatus = null,
        public ?array $validationResults = null,
        public ?array $metadata = null,
        public ?string $test = null,
        public ?string $objectCreated = null,
        public ?string $objectUpdated = null,
        public ?string $objectOwner = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            objectId: $data['object_id'],
            objectState: ObjectState::from($data['object_state']),
            name: $data['name'] ?? null,
            company: $data['company'] ?? null,
            street1: $data['street1'] ?? null,
            street2: $data['street2'] ?? null,
            street3: $data['street3'] ?? null,
            streetNo: $data['street_no'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            zip: $data['zip'] ?? null,
            country: $data['country'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            isResidential: $data['is_residential'] ?? null,
            validationStatus: isset($data['validation_results']['is_valid']) 
                ? ValidationStatus::from($data['validation_results']['is_valid']) 
                : null,
            validationResults: $data['validation_results'] ?? null,
            metadata: $data['metadata'] ?? null,
            test: $data['test'] ?? null,
            objectCreated: $data['object_created'] ?? null,
            objectUpdated: $data['object_updated'] ?? null,
            objectOwner: $data['object_owner'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'object_id' => $this->objectId,
            'object_state' => $this->objectState->value,
            'name' => $this->name,
            'company' => $this->company,
            'street1' => $this->street1,
            'street2' => $this->street2,
            'street3' => $this->street3,
            'street_no' => $this->streetNo,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_residential' => $this->isResidential,
            'validation_results' => $this->validationResults,
            'metadata' => $this->metadata,
        ], fn($value) => $value !== null);
    }
}
