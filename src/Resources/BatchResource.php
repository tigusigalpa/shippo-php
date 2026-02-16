<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

final class BatchResource extends BaseResource
{
    public function create(array $data): array
    {
        return $this->client->post('/batches', $data);
    }

    public function retrieve(string $batchId): array
    {
        return $this->client->get("/batches/{$batchId}");
    }

    public function addShipments(string $batchId, array $shipmentIds): array
    {
        return $this->client->post("/batches/{$batchId}/add_shipments", [
            'shipments' => $shipmentIds,
        ]);
    }

    public function purchase(string $batchId): array
    {
        return $this->client->post("/batches/{$batchId}/purchase");
    }

    public function list(array $params = []): array
    {
        return $this->client->get('/batches', $params);
    }
}
