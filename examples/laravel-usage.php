<?php

/**
 * Laravel Usage Examples
 * 
 * This file demonstrates how to use the Shippo SDK in a Laravel application.
 * These examples should be used in your Laravel controllers, services, or jobs.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tigusigalpa\Shippo\Laravel\Facades\Shippo;
use Tigusigalpa\Shippo\Exceptions\ShippoException;

class ShippingController extends Controller
{
    /**
     * Create a shipping label for an order.
     */
    public function createLabel(Request $request)
    {
        try {
            // Create shipment
            $shipment = Shippo::shipments()->create([
                'address_from' => [
                    'name' => config('app.name'),
                    'street1' => '123 Business St',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'zip' => '94103',
                    'country' => 'US',
                ],
                'address_to' => [
                    'name' => $request->input('customer_name'),
                    'street1' => $request->input('address_line1'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'zip' => $request->input('zip'),
                    'country' => $request->input('country', 'US'),
                ],
                'parcels' => [
                    [
                        'length' => '10',
                        'width' => '8',
                        'height' => '4',
                        'distance_unit' => 'in',
                        'weight' => '5',
                        'mass_unit' => 'lb',
                    ],
                ],
            ]);

            // Get the cheapest rate
            $cheapestRate = collect($shipment->rates)
                ->sortBy('amount')
                ->first();

            // Purchase label
            $transaction = Shippo::transactions()->create([
                'rate' => $cheapestRate['object_id'],
                'label_file_type' => 'PDF',
            ]);

            return response()->json([
                'success' => true,
                'label_url' => $transaction->labelUrl,
                'tracking_number' => $transaction->trackingNumber,
            ]);

        } catch (ShippoException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Validate a customer's shipping address.
     */
    public function validateAddress(Request $request)
    {
        try {
            $address = Shippo::addresses()->validate([
                'name' => $request->input('name'),
                'street1' => $request->input('street1'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country', 'US'),
            ]);

            return response()->json([
                'valid' => $address->validationStatus->value === 'VALID',
                'address' => $address->toArray(),
            ]);

        } catch (ShippoException $e) {
            return response()->json([
                'valid' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get shipping rates for a cart.
     */
    public function getRates(Request $request)
    {
        try {
            $shipment = Shippo::shipments()->create([
                'address_from' => [
                    'zip' => '94103',
                    'country' => 'US',
                ],
                'address_to' => [
                    'zip' => $request->input('zip'),
                    'country' => $request->input('country', 'US'),
                ],
                'parcels' => [
                    [
                        'length' => '10',
                        'width' => '8',
                        'height' => '4',
                        'distance_unit' => 'in',
                        'weight' => $request->input('weight', '5'),
                        'mass_unit' => 'lb',
                    ],
                ],
            ]);

            $rates = collect($shipment->rates)->map(function ($rate) {
                return [
                    'id' => $rate['object_id'],
                    'provider' => $rate['provider'],
                    'service' => $rate['servicelevel_name'],
                    'amount' => $rate['amount'],
                    'currency' => $rate['currency'],
                    'days' => $rate['days'] ?? null,
                ];
            });

            return response()->json([
                'rates' => $rates,
            ]);

        } catch (ShippoException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Track a package.
     */
    public function trackPackage(string $carrier, string $trackingNumber)
    {
        try {
            $tracking = Shippo::tracking()->retrieve($carrier, $trackingNumber);

            return response()->json([
                'status' => $tracking->trackingStatus,
                'eta' => $tracking->eta,
                'history' => $tracking->trackingHistory,
            ]);

        } catch (ShippoException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
