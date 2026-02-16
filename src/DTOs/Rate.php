<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo\DTOs;

final readonly class Rate
{
    public function __construct(
        public string $objectId,
        public string $amount,
        public string $currency,
        public string $amountLocal,
        public string $currencyLocal,
        public string $provider,
        public string $providerImage75,
        public string $providerImage200,
        public string $servicelevelName,
        public string $servicelevelToken,
        public ?string $servicelevelTerms = null,
        public ?int $days = null,
        public ?string $arrivesBy = null,
        public ?string $durationTerms = null,
        public ?array $messages = null,
        public ?string $carrierAccount = null,
        public ?bool $test = null,
        public ?string $zone = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            objectId: $data['object_id'],
            amount: $data['amount'],
            currency: $data['currency'],
            amountLocal: $data['amount_local'],
            currencyLocal: $data['currency_local'],
            provider: $data['provider'],
            providerImage75: $data['provider_image_75'],
            providerImage200: $data['provider_image_200'],
            servicelevelName: $data['servicelevel_name'],
            servicelevelToken: $data['servicelevel_token'],
            servicelevelTerms: $data['servicelevel_terms'] ?? null,
            days: $data['days'] ?? null,
            arrivesBy: $data['arrives_by'] ?? null,
            durationTerms: $data['duration_terms'] ?? null,
            messages: $data['messages'] ?? null,
            carrierAccount: $data['carrier_account'] ?? null,
            test: $data['test'] ?? null,
            zone: $data['zone'] ?? null,
        );
    }
}
