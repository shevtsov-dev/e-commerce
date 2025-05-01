<?php

declare(strict_types=1);

namespace App\Services\Currency;

class CurrencyRateParser
{
    /**
     * @const FILIAL_KEYS
     */
    private const FILIAL_KEYS = [
        'ratesKey'      => 'filials',
        'ratesValue'    => 'filial',
        'filialKey'     => 'rates',
        'filialValue'   => 'value',
        'valueKey'      => '@attributes',
        'valueCurrency' => 'iso',
        'valuePrice'    => 'sale',
    ];

    /**
     * @param array<string, mixed> $rates
     *
     * @return array<array{values: array<array{currency?: string, price?: mixed}>}>
     */
    public function parseFilials(array $rates): array
    {
        if (!isset($rates[self::FILIAL_KEYS['ratesKey']][self::FILIAL_KEYS['ratesValue']])) {
            throw new \InvalidArgumentException('Missing filials in the provided rates data.');
        }

        $filials = [];

        foreach ($rates[self::FILIAL_KEYS['ratesKey']][self::FILIAL_KEYS['ratesValue']] as $filial) {
            $values = [];

            foreach ($filial[self::FILIAL_KEYS['filialKey']][self::FILIAL_KEYS['filialValue']] ?? [] as $value) {
                $values[] = [
                    'currency' => $value[self::FILIAL_KEYS['valueKey']][self::FILIAL_KEYS['valueCurrency']] ?? null,
                    'price'    => $value[self::FILIAL_KEYS['valueKey']][self::FILIAL_KEYS['valuePrice']]    ?? null,
                ];
            }

            $filials[] = [
                'values' => $values,
            ];
        }

        return $filials;
    }
}
