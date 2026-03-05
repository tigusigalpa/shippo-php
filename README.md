# Shippo PHP SDK — Modern Shipping Integration for PHP and Laravel

![Shippo PHP SDK](https://github.com/user-attachments/assets/2a878c70-a364-42ee-a67e-dc20d8a2f7c2)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tigusigalpa/shippo-php.svg?style=flat-square)](https://packagist.org/packages/tigusigalpa/shippo-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/tigusigalpa/shippo-php/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tigusigalpa/shippo-php/actions/workflows/tests.yml)
[![License](https://img.shields.io/packagist/l/tigusigalpa/shippo-php.svg?style=flat-square)](https://packagist.org/packages/tigusigalpa/shippo-php)

If you're building an e-commerce platform, a warehouse management system, or any PHP application that needs to ship physical products, you know how painful it can be to integrate with carrier APIs directly. Each carrier has its own format, its own quirks, and its own set of headaches.

**Shippo PHP SDK** takes that pain away. It gives you a single, clean interface to work with dozens of shipping carriers through the [Shippo API](https://goshippo.com/) — and it does it in a way that feels natural to PHP and Laravel developers.

This package lets you compare shipping rates across carriers, generate and print shipping labels, validate addresses before they cause delivery failures, track packages in real time, handle customs for international shipments, and much more — all from your PHP code.

## Why Use This SDK?

There are a few ways to talk to the Shippo API from PHP. You could use raw HTTP requests, or you could reach for the official client. But this SDK was built with a different philosophy in mind: **it should feel like a first-class PHP package**, not a thin wrapper around REST calls.

Here's what sets it apart:

- **Built for modern PHP (8.1+)** — Takes full advantage of readonly properties, enums, named arguments, and strict typing. No legacy baggage.
- **Fully PSR-compliant** — Follows PSR-4 (autoloading), PSR-7 (HTTP messages), PSR-17 (HTTP factories), and PSR-18 (HTTP clients). You're never locked into a specific HTTP library.
- **Type-safe from top to bottom** — Every API response is mapped to a strongly-typed Data Transfer Object. Your IDE will autocomplete everything, and your static analysis tools will love it.
- **Smart retry logic built in** — When Shippo rate-limits your requests, the SDK automatically backs off and retries with exponential delays. You don't have to write that logic yourself.
- **First-class Laravel support** — Comes with a Service Provider, a Facade, and auto-discovery out of the box. If you use Laravel, setup takes under a minute.
- **Thoroughly tested** — The test suite is written with Pest PHP and covers the core functionality. PHPStan is configured at the strictest level (level 8).
- **Well-documented** — Clear PHPDoc annotations on every public method, plus real-world examples you can copy and adapt.
- **HTTP client agnostic** — Prefer Guzzle? Symfony HttpClient? Something else entirely? As long as it implements PSR-18, it works.

## Requirements

- **PHP 8.1** or higher
- **Laravel 9, 10, 11, or 12** (only if you want the Laravel integration — the core SDK works standalone)

## Installation

Getting started takes just one command:

```bash
composer require tigusigalpa/shippo-php
```

That's it. Composer will pull in the package and its dependencies.

### Setting Up with Laravel

If you're using Laravel, the package registers itself automatically thanks to Laravel's package auto-discovery — no need to touch `config/app.php`.

To customize the configuration, publish the config file:

```bash
php artisan vendor:publish --tag=shippo-config
```

Then add your Shippo API credentials to your `.env` file:

```env
SHIPPO_API_TOKEN=your_shippo_api_token_here
SHIPPO_IS_TEST=true
```

Set `SHIPPO_IS_TEST` to `true` while you're developing. Switch it to `false` when you're ready to go live with real shipments.

## Quick Start

### Using the SDK Standalone (Without Laravel)

You can use this SDK in any PHP project — it doesn't require Laravel at all. Here's how to set it up with Guzzle as the HTTP client:

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

Notice how the response is a real typed object, not a raw array. You get `$address->objectId`, `$address->city`, and so on — with full IDE autocompletion.

### Using the SDK with Laravel

In Laravel, things are even simpler. Just use the Facade:

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

No manual configuration, no service container bindings to set up — it just works.

## Real-World Usage Examples

Below are practical examples covering the most common shipping scenarios you'll encounter in production.

### Validating an Address Before Shipping

Bad addresses are one of the top reasons packages get returned or delayed. Validating addresses upfront saves money and keeps your customers happy:

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

This is especially useful in checkout flows — validate the address before the customer places the order, and you'll avoid a lot of headaches down the line.

### Creating a Shipping Label (Step by Step)

This is probably the most common workflow: create a shipment, pick a rate, and buy the label.

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

The `labelUrl` gives you a direct link to a printable PDF. The `trackingNumber` is what you share with your customer so they can follow their package.

### Tracking a Package

Give your customers real-time visibility into where their order is:

```php
$tracking = $shippo->tracking()->retrieve('usps', '9205590164917312751089');

echo "Status: {$tracking->trackingStatus}\n";
echo "ETA: {$tracking->eta}\n";

foreach ($tracking->trackingHistory as $event) {
    echo "{$event['status_date']} - {$event['status_details']}\n";
}
```

You can use this to build a tracking page on your site, send status update emails, or feed data into your order management system.

### Handling International Shipments with Customs Declarations

Shipping internationally? You'll need customs information. The SDK makes this straightforward:

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

Without proper customs declarations, international packages can get stuck at the border or returned to sender. This SDK handles the entire flow so you don't have to piece it together manually.

### Listing Addresses with Pagination

If you're managing a lot of addresses (for example, a customer address book), pagination keeps things efficient:

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

The `PaginatedCollection` is iterable and countable, so you can use it in `foreach` loops, pass it to Blade views, or serialize it to JSON for an API response.

### Refunding a Shipping Label

Made a mistake? Need to cancel a shipment? You can request a refund for unused labels:

```php
$refund = $shippo->refunds()->create('transaction_id_here');

echo "Refund status: {$refund['status']}\n";
```

Refund policies vary by carrier, but Shippo handles the communication — you just need to provide the transaction ID.

### Batch Label Creation for High-Volume Shipping

If you're shipping dozens or hundreds of packages at once, creating labels one by one is too slow. Batch operations let you handle bulk shipments efficiently:

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

This is ideal for fulfillment centers, subscription box services, or any business that processes a large number of orders daily.

## Error Handling

Things don't always go smoothly — invalid addresses, expired tokens, rate limits, server outages. The SDK gives you a clear exception hierarchy so you can handle each situation appropriately:

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

The `RateLimitException` is worth noting: the SDK automatically retries rate-limited requests with exponential backoff. It only throws this exception if all retry attempts are exhausted, so in most cases you won't even see it.

## Configuration

### Standalone Configuration

When using the SDK outside of Laravel, you can fine-tune the behavior through the `Config` object:

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

- **`is_test`** — Set to `true` during development to use Shippo's sandbox environment. No real charges, no real labels.
- **`timeout`** — How long (in seconds) to wait for a response before giving up.
- **`retry_attempts`** — How many times to retry a failed request before throwing an exception.
- **`retry_delay`** — The base delay (in milliseconds) between retries. Each subsequent retry doubles this value (exponential backoff).

### Laravel Configuration

After publishing the config, you'll find `config/shippo.php` in your project. All values can be overridden via environment variables:

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

This follows the standard Laravel convention of keeping secrets and environment-specific settings in `.env` rather than hardcoding them.

## Testing

The SDK includes a comprehensive test suite powered by [Pest PHP](https://pestphp.com/). Running the tests is straightforward:

```bash
composer test
```

To generate a code coverage report:

```bash
composer test-coverage
```

For static analysis with PHPStan (configured at the strictest level 8):

```bash
composer analyse
```

If you're contributing to this package, please make sure all tests pass and PHPStan reports no errors before submitting a pull request.

## Complete API Resource Reference

The SDK covers every major endpoint in the Shippo API. Here's what you can do with each resource:

- **Addresses** — Create, retrieve, update, delete, and validate shipping addresses. Supports both domestic and international formats.
- **Shipments** — Create shipments with origin, destination, and parcel details to get real-time shipping rates from multiple carriers.
- **Rates** — Retrieve and compare rates across carriers like USPS, UPS, FedEx, DHL, and many others.
- **Transactions** — Purchase shipping labels. Each transaction gives you a printable label and a tracking number.
- **Tracking** — Look up the current status of any package by carrier and tracking number. You can also register webhooks for push-based tracking updates.
- **Parcels** — Define and manage parcel templates with dimensions and weight for reuse across shipments.
- **Customs** — Create customs items and declarations required for international shipping. Handles tariff codes, item values, and certifications.
- **Refunds** — Request refunds for unused or voided shipping labels.
- **Manifests** — Generate end-of-day manifests (SCAN forms) required by some carriers for package pickup.
- **Carrier Accounts** — Connect and manage your carrier accounts (USPS, UPS, FedEx, DHL, etc.) directly through the API.
- **Batches** — Create and manage bulk label operations for high-volume shipping workflows.
- **Orders** — Import and manage orders for streamlined fulfillment.

## Architecture and Design Decisions

This SDK was designed with a few guiding principles that might be useful to understand if you're evaluating it for your project:

- **Immutable DTOs** — All Data Transfer Objects use PHP 8.1 readonly properties. Once created, they can't be accidentally modified, which eliminates a whole class of bugs.
- **Resource-based API** — Each Shippo API endpoint is represented by a dedicated Resource class (`AddressResource`, `ShipmentResource`, etc.), keeping the codebase organized and easy to navigate.
- **No hard dependency on a specific HTTP client** — The SDK depends on PSR interfaces, not concrete implementations. You bring your own HTTP client, and the SDK works with it.
- **Enum-backed constants** — Status values, label file types, and validation states are represented as PHP enums, giving you compile-time safety and better IDE support.

## Frequently Asked Questions

**Can I use this SDK without Laravel?**
Yes. The core SDK is framework-agnostic. Laravel support is an optional add-on that activates automatically when it detects a Laravel environment. You can use this package with any PHP 8.1+ project — Symfony, Slim, custom frameworks, or plain PHP scripts.

**Which carriers does Shippo support?**
Shippo supports 40+ carriers worldwide including USPS, UPS, FedEx, DHL Express, Canada Post, Australia Post, Royal Mail, and many more. The full list is available in the [Shippo documentation](https://goshippo.com/carriers).

**Is the sandbox/test mode really free?**
Yes. When you set `is_test` to `true`, all API calls go to Shippo's sandbox. No real labels are created, no charges are incurred. It's the recommended way to develop and test your integration.

**How does the retry logic work?**
When the SDK receives a 429 (Too Many Requests) response from Shippo, it waits and retries automatically. The wait time doubles with each attempt (exponential backoff). You can configure the number of retry attempts and the initial delay. If all retries fail, a `RateLimitException` is thrown.

**Can I use Symfony HttpClient instead of Guzzle?**
Absolutely. Any PSR-18 compatible HTTP client will work. Just pass your client instance to the `Shippo` constructor. If you're using Symfony, the `symfony/http-client` with `nyholm/psr7` is a great combination.

## Contributing

Contributions, bug reports, and feature requests are welcome. Whether it's a typo in the docs, a bug fix, or a whole new feature — every contribution helps make this package better for everyone.

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for detailed guidelines.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover a security vulnerability, please report it responsibly by emailing sovletig@gmail.com. Please do not use the public issue tracker for security issues — it helps protect users while the fix is being prepared.

## Credits

- [Igor Sazonov](https://github.com/tigusigalpa) — Creator and maintainer
- [All Contributors](../../contributors)

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md). You're free to use it in personal and commercial projects.

## Useful Links

- [Shippo API Documentation](https://docs.goshippo.com/) — Official API reference
- [Shippo Dashboard](https://apps.goshippo.com/) — Manage your Shippo account and carriers
- [Package on GitHub](https://github.com/tigusigalpa/shippo-php) — Source code, issues, and discussions
- [Package on Packagist](https://packagist.org/packages/tigusigalpa/shippo-php) — Composer package page

## Changelog

All notable changes are documented in [CHANGELOG.md](CHANGELOG.md). This project follows [Semantic Versioning](https://semver.org/).
