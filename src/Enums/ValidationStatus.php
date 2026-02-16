<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Enums;

enum ValidationStatus: string
{
    case VALID = 'VALID';
    case INVALID = 'INVALID';
    case UNKNOWN = 'UNKNOWN';
}
