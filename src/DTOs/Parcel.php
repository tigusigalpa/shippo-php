<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

final readonly class Parcel
{
    public function __construct(
        public string $objectId,
        public string $objectOwner,
        public string $objectCreated,
        public string $objectUpdated,
        public string $length,
        public string $width,
        public string $height,
        public string $distanceUnit,
        public string $weight,
        public string $massUnit,
        public ?array $metadata = null,
        public ?string $template = null,
        public ?array $extra = null,
        public ?bool $test = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            objectId: $data['object_id'],
            objectOwner: $data['object_owner'],
            objectCreated: $data['object_created'],
            objectUpdated: $data['object_updated'],
            length: $data['length'],
            width: $data['width'],
            height: $data['height'],
            distanceUnit: $data['distance_unit'],
            weight: $data['weight'],
            massUnit: $data['mass_unit'],
            metadata: $data['metadata'] ?? null,
            template: $data['template'] ?? null,
            extra: $data['extra'] ?? null,
            test: $data['test'] ?? null,
        );
    }
}
