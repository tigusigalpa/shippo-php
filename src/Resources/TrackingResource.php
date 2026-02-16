<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\DTOs\Tracking;

final class TrackingResource extends BaseResource
{
    public function create(string $carrier, string $trackingNumber, array $metadata = []): Tracking
    {
        $response = $this->client->post('/tracks', [
            'carrier' => $carrier,
            'tracking_number' => $trackingNumber,
            'metadata' => $metadata,
        ]);

        return Tracking::fromArray($response);
    }

    public function retrieve(string $carrier, string $trackingNumber): Tracking
    {
        $response = $this->client->get("/tracks/{$carrier}/{$trackingNumber}");

        return Tracking::fromArray($response);
    }
}
