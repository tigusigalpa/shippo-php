<?php

declare(strict_types=1);

use Tigusigalpa\Shippo\Resources\AddressResource;
use Tigusigalpa\Shippo\Resources\BatchResource;
use Tigusigalpa\Shippo\Resources\CarrierAccountResource;
use Tigusigalpa\Shippo\Resources\CustomsResource;
use Tigusigalpa\Shippo\Resources\ManifestResource;
use Tigusigalpa\Shippo\Resources\OrderResource;
use Tigusigalpa\Shippo\Resources\ParcelResource;
use Tigusigalpa\Shippo\Resources\RateResource;
use Tigusigalpa\Shippo\Resources\RefundResource;
use Tigusigalpa\Shippo\Resources\ShipmentResource;
use Tigusigalpa\Shippo\Resources\TrackingResource;
use Tigusigalpa\Shippo\Resources\TransactionResource;

test('shippo client provides addresses resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->addresses())->toBeInstanceOf(AddressResource::class);
});

test('shippo client provides shipments resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->shipments())->toBeInstanceOf(ShipmentResource::class);
});

test('shippo client provides rates resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->rates())->toBeInstanceOf(RateResource::class);
});

test('shippo client provides transactions resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->transactions())->toBeInstanceOf(TransactionResource::class);
});

test('shippo client provides tracking resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->tracking())->toBeInstanceOf(TrackingResource::class);
});

test('shippo client provides parcels resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->parcels())->toBeInstanceOf(ParcelResource::class);
});

test('shippo client provides customs resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->customs())->toBeInstanceOf(CustomsResource::class);
});

test('shippo client provides refunds resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->refunds())->toBeInstanceOf(RefundResource::class);
});

test('shippo client provides manifests resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->manifests())->toBeInstanceOf(ManifestResource::class);
});

test('shippo client provides carrier accounts resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->carrierAccounts())->toBeInstanceOf(CarrierAccountResource::class);
});

test('shippo client provides batches resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->batches())->toBeInstanceOf(BatchResource::class);
});

test('shippo client provides orders resource', function () {
    $shippo = $this->createShippoClient();

    expect($shippo->orders())->toBeInstanceOf(OrderResource::class);
});
