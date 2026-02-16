<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

final class CustomsResource extends BaseResource
{
    public function createItem(array $data): array
    {
        return $this->client->post('/customs/items', $data);
    }

    public function createDeclaration(array $data): array
    {
        return $this->client->post('/customs/declarations', $data);
    }

    public function retrieveItem(string $itemId): array
    {
        return $this->client->get("/customs/items/{$itemId}");
    }

    public function retrieveDeclaration(string $declarationId): array
    {
        return $this->client->get("/customs/declarations/{$declarationId}");
    }

    public function listItems(array $params = []): array
    {
        return $this->client->get('/customs/items', $params);
    }

    public function listDeclarations(array $params = []): array
    {
        return $this->client->get('/customs/declarations', $params);
    }
}
