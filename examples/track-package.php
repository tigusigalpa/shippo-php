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

$carrier = $argv[1] ?? 'usps';
$trackingNumber = $argv[2] ?? '9205590164917312751089';

try {
    echo "Tracking package...\n";
    echo "Carrier: {$carrier}\n";
    echo "Tracking Number: {$trackingNumber}\n\n";
    
    $tracking = $shippo->tracking()->retrieve($carrier, $trackingNumber);

    echo "Current Status: {$tracking->trackingStatus}\n";
    echo "ETA: " . ($tracking->eta ?? 'N/A') . "\n";
    echo "Service Level: " . ($tracking->servicelevel ?? 'N/A') . "\n\n";

    if ($tracking->trackingHistory) {
        echo "Tracking History:\n";
        echo str_repeat('-', 80) . "\n";
        
        foreach ($tracking->trackingHistory as $event) {
            echo sprintf(
                "%s | %s | %s\n",
                $event['status_date'] ?? 'N/A',
                $event['status'] ?? 'N/A',
                $event['status_details'] ?? 'N/A'
            );
            
            if (isset($event['location'])) {
                echo "  Location: {$event['location']['city']}, {$event['location']['state']}\n";
            }
            
            echo "\n";
        }
    }

} catch (\Tigusigalpa\Shippo\Exceptions\ShippoException $e) {
    echo "Error: {$e->getMessage()}\n";
    exit(1);
}
