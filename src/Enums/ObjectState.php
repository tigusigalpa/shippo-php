<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Enums;

enum ObjectState: string
{
    case VALID = 'VALID';
    case INVALID = 'INVALID';
    case QUEUED = 'QUEUED';
    case SUCCESS = 'SUCCESS';
    case ERROR = 'ERROR';
}
