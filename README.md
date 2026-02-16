# Shippo PHP SDK

![Shippo PHP SDK](https://github.com/user-attachments/assets/2a878c70-a364-42ee-a67e-dc20d8a2f7c2)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tigusigalpa/shippo-php.svg?style=flat-square)](https://packagist.org/packages/tigusigalpa/shippo-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/tigusigalpa/shippo-php/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tigusigalpa/shippo-php/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/tigusigalpa/shippo-php.svg?style=flat-square)](https://packagist.org/packages/tigusigalpa/shippo-php)
[![License](https://img.shields.io/packagist/l/tigusigalpa/shippo-php.svg?style=flat-square)](https://packagist.org/packages/tigusigalpa/shippo-php)

A modern, PSR-compliant PHP SDK for the [Shippo API](https://goshippo.com/) with seamless Laravel integration. This
library provides a developer-friendly interface for managing shipping labels, tracking, rates, and all Shippo API
functionality.

## Features

- 🚀 **Modern PHP 8.1+** with strict types and readonly properties
- 📦 **PSR Compliant** - PSR-4, PSR-7, PSR-17, PSR-18
- 🎯 **Type-Safe DTOs** - Strongly-typed Data Transfer Objects for all API responses
- 🔄 **Automatic Retry Logic** - Built-in exponential backoff for rate limits
- 🎨 **Laravel Integration** - Service Provider, Facade, and auto-discovery
- ✅ **Comprehensive Tests** - Full test coverage with Pest PHP
- 📚 **Excellent Documentation** - Clear examples and PHPDoc annotations
- 🔌 **HTTP Client Agnostic** - Use any PSR-18 compatible HTTP client

## Requirements

- PHP 8.1 or higher
- Laravel 9, 10, 11, or 12 (for Laravel integration)

## Installation

Install the package via Composer:

```bash
composer require tigusigalpa/shippo-php
```

### Laravel Installation

The package will automatically register itself via Laravel's package auto-discovery.

Publish the configuration file:

```bash
php artisan vendor:publish --tag=shippo-config
```

Add your Shippo API token to your `.env` file:

```env
SHIPPO_API_TOKEN=your_shippo_api_token_here
SHIPPO_IS_TEST=true
```

## Quick Start

### Standalone Usage

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Tigusigalpa\Shippo\Shippo;
use Tigusigalpa\Shippo\Config;

$httpFactory = new HttpFactory();
$config = Config::make('your_api_token');

$shippo = new Shippo(
    httpClient: new GuzzleClient(),
    requestFactory: $httpFactory,
    streamFactory: $httpFactory,
    config: $config
);

// Create an address
$address = $shippo->addresses()->create([
    'name' => 'John Doe',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
    'phone' => '+1 555 341 9393',
    'email' => 'john@example.com',
]);

echo $address->objectId;
```

### Laravel Usage

```php
use Tigusigalpa\Shippo\Laravel\Facades\Shippo;

// Create a shipment and get rates
$shipment = Shippo::shipments()->create([
    'address_from' => [
        'name' => 'Sender Name',
        'street1' => '123 Main St',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94103',
        'country' => 'US',
    ],
    'address_to' => [
        'name' => 'Recipient Name',
        'street1' => '456 Oak Ave',
        'city' => 'Los Angeles',
        'state' => 'CA',
        'zip' => '90001',
        'country' => 'US',
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

// Get available rates
foreach ($shipment->rates as $rate) {
    echo "{$rate['provider']} - {$rate['servicelevel_name']}: \${$rate['amount']}\n";
}
```

## Usage Examples

### Address Validation

```php
$validatedAddress = $shippo->addresses()->validate([
    'name' => 'John Doe',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
]);

if ($validatedAddress->validationStatus === ValidationStatus::VALID) {
    echo "Address is valid!";
}
```

### Creating a Shipping Label

```php
// 1. Create a shipment
$shipment = $shippo->shipments()->create([
    'address_from' => [
        'name' => 'Your Store',
        'street1' => '123 Business St',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94103',
        'country' => 'US',
    ],
    'address_to' => [
        'name' => 'Customer Name',
        'street1' => '456 Customer Ave',
        'city' => 'Los Angeles',
        'state' => 'CA',
        'zip' => '90001',
        'country' => 'US',
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

// 2. Select a rate (e.g., the cheapest one)
$rateId = $shipment->rates[0]['object_id'];

// 3. Purchase the label
$transaction = $shippo->transactions()->create([
    'rate' => $rateId,
    'label_file_type' => 'PDF',
    'async' => false,
]);

// 4. Get the label URL
echo "Label URL: {$transaction->labelUrl}\n";
echo "Tracking Number: {$transaction->trackingNumber}\n";
```

### Tracking a Package

```php
$tracking = $shippo->tracking()->retrieve('usps', '9205590164917312751089');

echo "Status: {$tracking->trackingStatus}\n";
echo "ETA: {$tracking->eta}\n";

foreach ($tracking->trackingHistory as $event) {
    echo "{$event['status_date']} - {$event['status_details']}\n";
}
```

### International Shipments with Customs

```php
// Create customs items
$customsItem = $shippo->customs()->createItem([
    'description' => 'T-Shirt',
    'quantity' => 2,
    'net_weight' => '0.5',
    'mass_unit' => 'lb',
    'value_amount' => '20.00',
    'value_currency' => 'USD',
    'origin_country' => 'US',
]);

// Create customs declaration
$customsDeclaration = $shippo->customs()->createDeclaration([
    'contents_type' => 'MERCHANDISE',
    'contents_explanation' => 'T-Shirts',
    'non_delivery_option' => 'RETURN',
    'certify' => true,
    'certify_signer' => 'John Doe',
    'items' => [$customsItem['object_id']],
]);

// Create shipment with customs
$shipment = $shippo->shipments()->create([
    'address_from' => [...],
    'address_to' => [...],
    'parcels' => [...],
    'customs_declaration' => $customsDeclaration['object_id'],
]);
```

### Listing Addresses with Pagination

```php
$addresses = $shippo->addresses()->list([
    'results' => 25,
    'page' => 1,
]);

foreach ($addresses as $address) {
    echo "{$address->name} - {$address->city}, {$address->state}\n";
}

if ($addresses->hasMorePages()) {
    echo "More addresses available!";
}
```

### Refunding a Label

```php
$refund = $shippo->refunds()->create('transaction_id_here');

echo "Refund status: {$refund['status']}\n";
```

### Batch Label Creation

```php
// Create a batch
$batch = $shippo->batches()->create([
    'default_carrier_account' => 'carrier_account_id',
    'default_servicelevel_token' => 'usps_priority',
    'label_filetype' => 'PDF_4x6',
]);

// Add shipments to batch
$shippo->batches()->addShipments($batch['object_id'], [
    'shipment_id_1',
    'shipment_id_2',
    'shipment_id_3',
]);

// Purchase all labels in batch
$result = $shippo->batches()->purchase($batch['object_id']);
```

## Error Handling

The SDK provides a comprehensive exception hierarchy for granular error handling:

```php
use Tigusigalpa\Shippo\Exceptions\AuthenticationException;
use Tigusigalpa\Shippo\Exceptions\RateLimitException;
use Tigusigalpa\Shippo\Exceptions\ValidationException;
use Tigusigalpa\Shippo\Exceptions\NotFoundException;
use Tigusigalpa\Shippo\Exceptions\ServerException;
use Tigusigalpa\Shippo\Exceptions\ShippoException;

try {
    $address = $shippo->addresses()->create([...]);
} catch (AuthenticationException $e) {
    // Invalid API token
    echo "Authentication failed: {$e->getMessage()}";
} catch (ValidationException $e) {
    // Invalid data provided
    echo "Validation error: {$e->getMessage()}";
    print_r($e->getResponse());
} catch (RateLimitException $e) {
    // Rate limit exceeded (automatically retried)
    echo "Rate limited. Retry after: {$e->getRetryAfter()} seconds";
} catch (NotFoundException $e) {
    // Resource not found
    echo "Not found: {$e->getMessage()}";
} catch (ServerException $e) {
    // Shippo server error
    echo "Server error: {$e->getMessage()}";
} catch (ShippoException $e) {
    // Any other Shippo error
    echo "Error: {$e->getMessage()}";
}
```

## Configuration

### Standalone Configuration

```php
$config = Config::make('your_api_token', [
    'api_version' => '2018-02-08',
    'base_url' => 'https://api.goshippo.com',
    'is_test' => true,
    'timeout' => 30,
    'retry_attempts' => 3,
    'retry_delay' => 1000, // milliseconds
]);
```

### Laravel Configuration

Edit `config/shippo.php`:

```php
return [
    'api_token' => env('SHIPPO_API_TOKEN'),
    'api_version' => env('SHIPPO_API_VERSION', '2018-02-08'),
    'base_url' => env('SHIPPO_BASE_URL', 'https://api.goshippo.com'),
    'is_test' => env('SHIPPO_IS_TEST', false),
    'timeout' => env('SHIPPO_TIMEOUT', 30),
    'retry_attempts' => env('SHIPPO_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('SHIPPO_RETRY_DELAY', 1000),
];
```

## Testing

Run the test suite:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

Run static analysis:

```bash
composer analyse
```

## API Resources

The SDK provides access to all Shippo API resources:

- **Addresses** - Create, validate, and manage addresses
- **Shipments** - Create shipments and get rates
- **Rates** - Retrieve and compare shipping rates
- **Transactions** - Purchase shipping labels
- **Tracking** - Track packages and register webhooks
- **Parcels** - Manage parcel templates
- **Customs** - Handle international shipping customs
- **Refunds** - Request label refunds
- **Manifests** - Create end-of-day manifests
- **Carrier Accounts** - Manage carrier account connections
- **Batches** - Bulk label creation
- **Orders** - Manage orders for fulfillment

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover any security-related issues, please email sovletig@gmail.com instead of using the issue tracker.

## Credits

- [Igor Sazonov](https://github.com/tigusigalpa)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Resources

- [Shippo API Documentation](https://docs.goshippo.com/)
- [Shippo Dashboard](https://apps.goshippo.com/)
- [Package Documentation](https://github.com/tigusigalpa/shippo-php)

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.
