<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;

final class RefundResource extends BaseResource
{
    public function create(string $transactionId): array
    {
        return $this->client->post('/refunds', [
            'transaction' => $transactionId,
        ]);
    }

    public function retrieve(string $refundId): array
    {
        return $this->client->get("/refunds/{$refundId}");
    }

    public function list(array $params = []): array
    {
        return $this->client->get('/refunds', $params);
    }
}
