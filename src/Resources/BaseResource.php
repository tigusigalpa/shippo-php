<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Resources;

use Tigusigalpa\Shippo\Client;

abstract class BaseResource
{
    public function __construct(
        protected readonly Client $client
    ) {
    }
}
