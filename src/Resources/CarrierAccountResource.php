<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

final class CarrierAccountResource extends BaseResource
{
    public function create(array $data): array
    {
        return $this->client->post('/carrier_accounts', $data);
    }

    public function retrieve(string $accountId): array
    {
        return $this->client->get("/carrier_accounts/{$accountId}");
    }

    public function list(array $params = []): array
    {
        return $this->client->get('/carrier_accounts', $params);
    }

    public function update(string $accountId, array $data): array
    {
        return $this->client->put("/carrier_accounts/{$accountId}", $data);
    }
}
