<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;

$apiToken = getenv('SHIPPO_API_TOKEN') ?: 'your_test_token_here';

$httpFactory = new HttpFactory();
$config = Config::make($apiToken, [
    'is_test' => true,
]);

$shippo = new Shippo(
    httpClient: new GuzzleClient(),
    requestFactory: $httpFactory,
    streamFactory: $httpFactory,
    config: $config
);

try {
    echo "Creating shipment...\n";
    
    $shipment = $shippo->shipments()->create([
        'address_from' => [
            'name' => 'Shippo Sender',
            'street1' => '215 Clayton St.',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94117',
            'country' => 'US',
            'phone' => '+1 555 341 9393',
            'email' => 'sender@example.com',
        ],
        'address_to' => [
            'name' => 'Shippo Recipient',
            'street1' => '965 Mission St.',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94105',
            'country' => 'US',
            'phone' => '+1 555 341 9393',
            'email' => 'recipient@example.com',
        ],
        'parcels' => [
            [
                'length' => '5',
                'width' => '5',
                'height' => '5',
                'distance_unit' => 'in',
                'weight' => '2',
                'mass_unit' => 'lb',
            ],
        ],
    ]);

    echo "Shipment created: {$shipment->objectId}\n\n";

    echo "Available rates:\n";
    foreach ($shipment->rates as $rate) {
        echo sprintf(
            "- %s %s: $%s %s (arrives in %s days)\n",
            $rate['provider'],
            $rate['servicelevel_name'],
            $rate['amount'],
            $rate['currency'],
            $rate['days'] ?? 'N/A'
        );
    }

    if (empty($shipment->rates)) {
        echo "No rates available.\n";
        exit(1);
    }

    $selectedRate = $shipment->rates[0];
    echo "\nPurchasing label with {$selectedRate['provider']} {$selectedRate['servicelevel_name']}...\n";

    $transaction = $shippo->transactions()->create([
        'rate' => $selectedRate['object_id'],
        'label_file_type' => 'PDF',
        'async' => false,
    ]);

    echo "\nLabel purchased successfully!\n";
    echo "Transaction ID: {$transaction->objectId}\n";
    echo "Tracking Number: {$transaction->trackingNumber}\n";
    echo "Label URL: {$transaction->labelUrl}\n";
    echo "Status: {$transaction->status}\n";

} catch (\Tigusigalpa\Shippo\Exceptions\ShippoException $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "Code: {$e->getCode()}\n";
    
    if ($response = $e->getResponse()) {
        echo "Response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";
    }
    
    exit(1);
}
