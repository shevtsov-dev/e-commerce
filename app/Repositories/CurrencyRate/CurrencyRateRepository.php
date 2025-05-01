<?php

declare(strict_types=1);

namespace App\Repositories\CurrencyRate;

use App\Models\CurrencyRate;

class CurrencyRateRepository implements Interfaces\CurrencyRateRepositoryInterface
{
    /**
     * @param array{currency: string} $conditions
     * @param array{rate: float}      $values
     *
     * @return void
     */
    public function updateOrCreate(array $conditions, array $values): void
    {
        CurrencyRate::query()->updateOrCreate($conditions, $values);
    }

    /**
     * @return array<string, float>
     */
    public function getAllRates(): array
    {
        return CurrencyRate::query()->pluck('rate', 'currency')->toArray();
    }
}
