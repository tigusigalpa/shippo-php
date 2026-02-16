<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

final class OrderResource extends BaseResource
{
    public function create(array $data): array
    {
        return $this->client->post('/orders', $data);
    }

    public function retrieve(string $orderId): array
    {
        return $this->client->get("/orders/{$orderId}");
    }

    public function list(array $params = []): array
    {
        return $this->client->get('/orders', $params);
    }
}
