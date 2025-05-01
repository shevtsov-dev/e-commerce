<?php

declare(strict_types=1);

namespace App\Services\Currency\Interfaces;

interface CurrencyRateUpdaterInterface
{
    /**
     * @param array<string, mixed> $rates
     *
     * @return void
     */
    public function update(array $rates): void;
}
