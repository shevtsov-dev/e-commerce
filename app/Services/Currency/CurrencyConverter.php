<?php

declare(strict_types=1);

namespace App\Services\Currency;

use App\Repositories\CurrencyRate\Interfaces\CurrencyRateRepositoryInterface;
use App\Services\Cache\CacheService;
use App\Services\Currency\Interfaces\CurrencyConverterInterface;
use Psr\Log\LoggerInterface;

readonly class CurrencyConverter implements CurrencyConverterInterface
{
    /**
     * @param CacheService                    $cacheService
     * @param LoggerInterface                 $logger
     * @param CurrencyRateRepositoryInterface $currencyRateRepository,
 */
    public function __construct(
        private CacheService $cacheService,
        protected LoggerInterface $logger,
        private CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @return array<string, float>|null
     */
    public function getRates(): ?array
    {
        return $this->cacheService->remember('currency_rates_tmp', now()->addHour(), function () {
            $rates = $this->currencyRateRepository->getAllRates();

            if (empty($rates)) {
                return null;
            }

            $this->cacheService->put('currency_rates', $rates, now()->addHour());

            return $rates;
        });
    }

    /**
     * @param float  $amount
     * @param string $toCurrency
     * @param int    $precision
     *
     * @return float|null
     */
    public function convert(float $amount, string $toCurrency, int $precision = 2): ?float
    {
        $rates = $this->getRates();

        if (!isset($rates[$toCurrency])) {
            $this->logger->warning(safe_trans('messages.currency_not_found', ['currency' => $toCurrency]));

            return null;
        }

        return round($amount / $rates[$toCurrency], $precision);
    }

    /**
     * @param float    $amount
     * @param string[] $currencies
     *
     * @return array<string, float|null>
     */
    public function convertMany(float $amount, array $currencies): array
    {
        $converted = [];
        foreach ($currencies as $currency) {
            $converted[$currency] = $this->convert($amount, $currency);
        }

        return $converted;
    }
}
