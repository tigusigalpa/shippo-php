<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Exceptions;

use Exception;

class ShippoException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        protected ?array $response = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }
}
