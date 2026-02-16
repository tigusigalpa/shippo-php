<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;
use Tigusigalpa\Shippo\DTOs\Transaction;

final class TransactionResource extends BaseResource
{
    public function create(array $data): Transaction
    {
        $response = $this->client->post('/transactions', $data);

        return Transaction::fromArray($response);
    }

    public function retrieve(string $transactionId): Transaction
    {
        $response = $this->client->get("/transactions/{$transactionId}");

        return Transaction::fromArray($response);
    }

    public function list(array $params = []): PaginatedCollection
    {
        $response = $this->client->get('/transactions', $params);

        return PaginatedCollection::fromArray($response, fn(array $item) => Transaction::fromArray($item));
    }
}
