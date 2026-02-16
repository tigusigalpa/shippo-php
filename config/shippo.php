<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shippo API Token
    |--------------------------------------------------------------------------
    |
    | Your Shippo API token. You can find this in your Shippo dashboard.
    | Use a test token for development and a live token for production.
    |
    */

    'api_token' => env('SHIPPO_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | The Shippo API version to use. Default is 2018-02-08.
    |
    */

    'api_version' => env('SHIPPO_API_VERSION', '2018-02-08'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Shippo API. You typically don't need to change this.
    |
    */

    'base_url' => env('SHIPPO_BASE_URL', 'https://api.goshippo.com'),

    /*
    |--------------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------------
    |
    | Whether to use test mode. When true, the SDK will use test tokens.
    |
    */

    'is_test' => env('SHIPPO_IS_TEST', false),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */

    'timeout' => env('SHIPPO_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic retry behavior for rate-limited requests.
    |
    */

    'retry_attempts' => env('SHIPPO_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('SHIPPO_RETRY_DELAY', 1000),

];
