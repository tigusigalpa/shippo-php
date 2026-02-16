<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tigusigalpa\Shippo\Resources\AddressResource;
use Tigusigalpa\Shippo\Resources\RateResource;
use Tigusigalpa\Shippo\Resources\ShipmentResource;
use Tigusigalpa\Shippo\Resources\TrackingResource;
use Tigusigalpa\Shippo\Resources\TransactionResource;
use Tigusigalpa\Shippo\Resources\ParcelResource;
use Tigusigalpa\Shippo\Resources\CustomsResource;
use Tigusigalpa\Shippo\Resources\RefundResource;
use Tigusigalpa\Shippo\Resources\ManifestResource;
use Tigusigalpa\Shippo\Resources\CarrierAccountResource;
use Tigusigalpa\Shippo\Resources\BatchResource;
use Tigusigalpa\Shippo\Resources\OrderResource;

final class Shippo
{
    private readonly Client $client;

    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        Config $config
    ) {
        $this->client = new Client($httpClient, $requestFactory, $streamFactory, $config);
    }

    public static function make(
        string $apiToken,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        array $options = []
    ): self {
        $config = Config::make($apiToken, $options);

        return new self($httpClient, $requestFactory, $streamFactory, $config);
    }

    public function addresses(): AddressResource
    {
        return new AddressResource($this->client);
    }

    public function shipments(): ShipmentResource
    {
        return new ShipmentResource($this->client);
    }

    public function rates(): RateResource
    {
        return new RateResource($this->client);
    }

    public function transactions(): TransactionResource
    {
        return new TransactionResource($this->client);
    }

    public function tracking(): TrackingResource
    {
        return new TrackingResource($this->client);
    }

    public function parcels(): ParcelResource
    {
        return new ParcelResource($this->client);
    }

    public function customs(): CustomsResource
    {
        return new CustomsResource($this->client);
    }

    public function refunds(): RefundResource
    {
        return new RefundResource($this->client);
    }

    public function manifests(): ManifestResource
    {
        return new ManifestResource($this->client);
    }

    public function carrierAccounts(): CarrierAccountResource
    {
        return new CarrierAccountResource($this->client);
    }

    public function batches(): BatchResource
    {
        return new BatchResource($this->client);
    }

    public function orders(): OrderResource
    {
        return new OrderResource($this->client);
    }
}
