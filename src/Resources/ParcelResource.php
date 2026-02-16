<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\Parcel;
use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

final class ParcelResource extends BaseResource
{
    public function create(array $data): Parcel
    {
        $response = $this->client->post('/parcels', $data);

        return Parcel::fromArray($response);
    }

    public function retrieve(string $parcelId): Parcel
    {
        $response = $this->client->get("/parcels/{$parcelId}");

        return Parcel::fromArray($response);
    }

    public function list(array $params = []): PaginatedCollection
    {
        $response = $this->client->get('/parcels', $params);

        return PaginatedCollection::fromArray($response, fn(array $item) => Parcel::fromArray($item));
    }
}
