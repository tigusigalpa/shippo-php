<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
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

/**
 * @method static AddressResource addresses()
 * @method static ShipmentResource shipments()
 * @method static RateResource rates()
 * @method static TransactionResource transactions()
 * @method static TrackingResource tracking()
 * @method static ParcelResource parcels()
 * @method static CustomsResource customs()
 * @method static RefundResource refunds()
 * @method static ManifestResource manifests()
 * @method static CarrierAccountResource carrierAccounts()
 * @method static BatchResource batches()
 * @method static OrderResource orders()
 *
 * @see \Tigusigalpa\Shippo\Shippo
 */
class Shippo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shippo';
    }
}
