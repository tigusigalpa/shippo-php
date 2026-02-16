# Setup Guide

This guide will help you get started with the Shippo PHP SDK.

## Prerequisites

- PHP 8.1 or higher
- Composer
- A Shippo account ([sign up here](https://goshippo.com/))

## Installation

### Step 1: Install via Composer

```bash
composer require tigusigalpa/shippo-php
```

### Step 2: Get Your API Token

1. Log in to your [Shippo Dashboard](https://apps.goshippo.com/)
2. Navigate to Settings → API
3. Copy your **Test Token** for development
4. Copy your **Live Token** for production

## Standalone PHP Usage

### Basic Setup

```php
<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;

// Create configuration
$config = Config::make('your_test_token_here', [
    'is_test' => true,
]);

// Initialize HTTP dependencies
$httpFactory = new HttpFactory();

// Create Shippo client
$shippo = new Shippo(
    httpClient: new GuzzleClient(),
    requestFactory: $httpFactory,
    streamFactory: $httpFactory,
    config: $config
);

// Use the client
$address = $shippo->addresses()->create([
    'name' => 'John Doe',
    'street1' => '215 Clayton St.',
    'city' => 'San Francisco',
    'state' => 'CA',
    'zip' => '94117',
    'country' => 'US',
]);

echo "Address created: {$address->objectId}\n";
```

## Laravel Usage

### Step 1: Publish Configuration

```bash
php artisan vendor:publish --tag=shippo-config
```

This creates `config/shippo.php`.

### Step 2: Configure Environment Variables

Add to your `.env` file:

```env
SHIPPO_API_TOKEN=shippo_test_your_token_here
SHIPPO_IS_TEST=true
```

### Step 3: Use the Facade

```php
<?php

namespace App\Http\Controllers;

use Tigusigalpa\Shippo\Laravel\Facades\Shippo;

class ShippingController extends Controller
{
    public function createLabel()
    {
        $shipment = Shippo::shipments()->create([
            'address_from' => [...],
            'address_to' => [...],
            'parcels' => [...],
        ]);

        return response()->json($shipment);
    }
}
```

### Step 4: Dependency Injection (Alternative)

```php
<?php

namespace App\Services;

use Tigusigalpa\Shippo\Shippo;

class ShippingService
{
    public function __construct(
        private Shippo $shippo
    ) {}

    public function createLabel(array $data)
    {
        return $this->shippo->shipments()->create($data);
    }
}
```

## Using a Different HTTP Client

The SDK is PSR-18 compliant, so you can use any compatible HTTP client:

### Symfony HTTP Client

```bash
composer require symfony/http-client nyholm/psr7
```

```php
use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

$psr17Factory = new Psr17Factory();
$httpClient = new Psr18Client();

$shippo = new Shippo(
    httpClient: $httpClient,
    requestFactory: $psr17Factory,
    streamFactory: $psr17Factory,
    config: $config
);
```

### Guzzle (Default)

```bash
composer require guzzlehttp/guzzle
```

```php
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;

$httpFactory = new HttpFactory();

$shippo = new Shippo(
    httpClient: new GuzzleClient(),
    requestFactory: $httpFactory,
    streamFactory: $httpFactory,
    config: $config
);
```

## Configuration Options

All available configuration options:

```php
$config = Config::make('your_api_token', [
    // API version to use
    'api_version' => '2018-02-08',
    
    // Base URL (usually don't need to change)
    'base_url' => 'https://api.goshippo.com',
    
    // Use test mode
    'is_test' => true,
    
    // Request timeout in seconds
    'timeout' => 30,
    
    // Number of retry attempts for rate limits
    'retry_attempts' => 3,
    
    // Delay between retries in milliseconds
    'retry_delay' => 1000,
]);
```

## Testing Your Setup

Create a test file `test-shippo.php`:

```php
<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Tigusigalpa\Shippo\Config;
use Tigusigalpa\Shippo\Shippo;

$config = Config::make('your_test_token_here', ['is_test' => true]);
$httpFactory = new HttpFactory();

$shippo = new Shippo(
    new GuzzleClient(),
    $httpFactory,
    $httpFactory,
    $config
);

try {
    $address = $shippo->addresses()->create([
        'name' => 'Test User',
        'street1' => '215 Clayton St.',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94117',
        'country' => 'US',
    ]);
    
    echo "✓ Success! Address ID: {$address->objectId}\n";
} catch (\Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
```

Run it:

```bash
php test-shippo.php
```

## Next Steps

- Read the [README.md](README.md) for comprehensive usage examples
- Check the [examples/](examples/) directory for more code samples
- Review the [Shippo API Documentation](https://docs.goshippo.com/)
- Join the discussion on [GitHub](https://github.com/tigusigalpa/shippo-php)

## Troubleshooting

### "Class not found" errors

Make sure you've run:
```bash
composer install
```

### Authentication errors

- Verify your API token is correct
- Ensure you're using a test token with `is_test => true`
- Check that your token hasn't expired

### Rate limit errors

The SDK automatically retries rate-limited requests. If you're still hitting limits:
- Increase `retry_attempts` in config
- Implement request queuing in your application
- Contact Shippo support for higher limits

### Laravel: Service provider not found

Make sure you've cleared your config cache:
```bash
php artisan config:clear
php artisan cache:clear
```

## Support

- **Documentation**: [README.md](README.md)
- **Issues**: [GitHub Issues](https://github.com/tigusigalpa/shippo-php/issues)
- **Email**: sovletig@gmail.com
- **Shippo Support**: [https://support.goshippo.com/](https://support.goshippo.com/)
