<?php

declare(strict_types=1);

namespace App\Services\Currency;

final readonly class CurrencyRateData
{
    /**
     * @param string $currency
     * @param float  $rate
     */
    public function __construct(
        public string $currency,
        public float  $rate,
    ) {
    }
}
