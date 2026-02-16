<?php

declare(strict_types=1);

use Tigusigalpa\Shippo\DTOs\Address;
use Tigusigalpa\Shippo\Enums\ObjectState;
use Tigusigalpa\Shippo\Enums\ValidationStatus;

test('address can be created from array', function () {
    $data = [
        'object_id' => 'addr_123',
        'object_state' => 'VALID',
        'name' => 'John Doe',
        'company' => 'Acme Corp',
        'street1' => '123 Main St',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94103',
        'country' => 'US',
        'phone' => '555-1234',
        'email' => 'john@example.com',
        'is_residential' => true,
        'validation_results' => [
            'is_valid' => 'VALID',
        ],
        'metadata' => ['customer_id' => '123'],
        'object_created' => '2024-01-01T00:00:00Z',
        'object_updated' => '2024-01-01T00:00:00Z',
    ];

    $address = Address::fromArray($data);

    expect($address->objectId)->toBe('addr_123')
        ->and($address->objectState)->toBe(ObjectState::VALID)
        ->and($address->name)->toBe('John Doe')
        ->and($address->company)->toBe('Acme Corp')
        ->and($address->street1)->toBe('123 Main St')
        ->and($address->city)->toBe('San Francisco')
        ->and($address->state)->toBe('CA')
        ->and($address->zip)->toBe('94103')
        ->and($address->country)->toBe('US')
        ->and($address->phone)->toBe('555-1234')
        ->and($address->email)->toBe('john@example.com')
        ->and($address->isResidential)->toBeTrue()
        ->and($address->validationStatus)->toBe(ValidationStatus::VALID)
        ->and($address->metadata)->toBe(['customer_id' => '123']);
});

test('address can be converted to array', function () {
    $address = new Address(
        objectId: 'addr_123',
        objectState: ObjectState::VALID,
        name: 'Jane Doe',
        street1: '456 Oak Ave',
        city: 'Los Angeles',
        state: 'CA',
        zip: '90001',
        country: 'US'
    );

    $array = $address->toArray();

    expect($array)->toHaveKey('object_id')
        ->and($array['object_id'])->toBe('addr_123')
        ->and($array)->toHaveKey('name')
        ->and($array['name'])->toBe('Jane Doe')
        ->and($array)->toHaveKey('street1')
        ->and($array['street1'])->toBe('456 Oak Ave');
});
