<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

final class ManifestResource extends BaseResource
{
    public function create(array $data): array
    {
        return $this->client->post('/manifests', $data);
    }

    public function retrieve(string $manifestId): array
    {
        return $this->client->get("/manifests/{$manifestId}");
    }

    public function list(array $params = []): array
    {
        return $this->client->get('/manifests', $params);
    }
}
