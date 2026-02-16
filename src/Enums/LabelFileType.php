<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\Enums;

enum LabelFileType: string
{
    case PDF = 'PDF';
    case PDF_4X6 = 'PDF_4x6';
    case PNG = 'PNG';
    case ZPLII = 'ZPLII';
}
