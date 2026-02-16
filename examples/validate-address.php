<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;
use Tigusigalpa\Shippo\Enums\ValidationStatus;

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
    echo "Validating address...\n\n";
    
    $address = $shippo->addresses()->validate([
        'name' => 'Shippo User',
        'street1' => '215 Clayton St.',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94117',
        'country' => 'US',
    ]);

    echo "Address ID: {$address->objectId}\n";
    echo "Validation Status: {$address->validationStatus?->value}\n\n";

    if ($address->validationStatus === ValidationStatus::VALID) {
        echo "✓ Address is valid!\n\n";
        echo "Validated Address:\n";
        echo "  Name: {$address->name}\n";
        echo "  Street: {$address->street1}\n";
        echo "  City: {$address->city}\n";
        echo "  State: {$address->state}\n";
        echo "  ZIP: {$address->zip}\n";
        echo "  Country: {$address->country}\n";
    } else {
        echo "✗ Address validation failed\n\n";
        
        if ($address->validationResults) {
            echo "Validation Messages:\n";
            foreach ($address->validationResults['messages'] ?? [] as $message) {
                echo "  - {$message['text']}\n";
            }
        }
    }

} catch (\Tigusigalpa\Shippo\Exceptions\ShippoException $e) {
    echo "Error: {$e->getMessage()}\n";
    exit(1);
}
