<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Exceptions;

class RateLimitException extends ShippoException
{
    public function __construct(
        string $message = 'Rate limit exceeded',
        int $code = 429,
        ?\Exception $previous = null,
        ?array $response = null,
        protected ?int $retryAfter = null
    ) {
        parent::__construct($message, $code, $previous, $response);
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
}
