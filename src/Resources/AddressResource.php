<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\Address;
use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

final class AddressResource extends BaseResource
{
    public function create(array $data): Address
    {
        $response = $this->client->post('/addresses', $data);

        return Address::fromArray($response);
    }

    public function retrieve(string $addressId): Address
    {
        $response = $this->client->get("/addresses/{$addressId}");

        return Address::fromArray($response);
    }

    public function list(array $params = []): PaginatedCollection
    {
        $response = $this->client->get('/addresses', $params);

        return PaginatedCollection::fromArray($response, fn(array $item) => Address::fromArray($item));
    }

    public function update(string $addressId, array $data): Address
    {
        $response = $this->client->put("/addresses/{$addressId}", $data);

        return Address::fromArray($response);
    }

    public function delete(string $addressId): array
    {
        return $this->client->delete("/addresses/{$addressId}");
    }

    public function validate(array $addressData): Address
    {
        $response = $this->client->get('/addresses/validate', $addressData);

        return Address::fromArray($response);
    }
}
