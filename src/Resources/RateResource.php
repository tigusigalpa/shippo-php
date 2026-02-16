<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\PaginatedCollection;
use Tigusigalpa\Shippo\DTOs\Rate;

final class RateResource extends BaseResource
{
    public function retrieve(string $rateId): Rate
    {
        $response = $this->client->get("/rates/{$rateId}");

        return Rate::fromArray($response);
    }

    public function list(array $params = []): PaginatedCollection
    {
        $response = $this->client->get('/rates', $params);

        return PaginatedCollection::fromArray($response, fn(array $item) => Rate::fromArray($item));
    }
}
