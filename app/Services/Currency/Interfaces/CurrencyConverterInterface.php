<?php

declare(strict_types=1);

namespace App\Services\Currency\Interfaces;

interface CurrencyConverterInterface
{
    /**
     * @return array<string, float>|null
     */
    public function getRates(): ?array;

    /**
     * @param float  $amount
     * @param string $toCurrency
     * @param int    $precision
     *
     * @return float|null
     */
    public function convert(float $amount, string $toCurrency, int $precision = 2): ?float;

    /**
     * @param float    $amount
     * @param string[] $currencies
     *
     * @return array<string, float|null>
     */
    public function convertMany(float $amount, array $currencies): array;
}
