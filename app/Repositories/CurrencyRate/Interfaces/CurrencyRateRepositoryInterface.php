<?php

declare(strict_types=1);

namespace App\Repositories\CurrencyRate\Interfaces;

interface CurrencyRateRepositoryInterface
{
    /**
     * @param array{currency: string} $conditions
     * @param array{rate: float}      $values
     *
     * @return void
     */
    public function updateOrCreate(array $conditions, array $values): void;

    /**
     * @return array<string, float>
     */
    public function getAllRates(): array;
}
