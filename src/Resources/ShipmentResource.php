<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;
use Tigusigalpa\Shippo\DTOs\Shipment;

final class ShipmentResource extends BaseResource
{
    public function create(array $data): Shipment
    {
        $response = $this->client->post('/shipments', $data);

        return Shipment::fromArray($response);
    }

    public function retrieve(string $shipmentId): Shipment
    {
        $response = $this->client->get("/shipments/{$shipmentId}");

        return Shipment::fromArray($response);
    }

    public function list(array $params = []): PaginatedCollection
    {
        $response = $this->client->get('/shipments', $params);

        return PaginatedCollection::fromArray($response, fn(array $item) => Shipment::fromArray($item));
    }
}
