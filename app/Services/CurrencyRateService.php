<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Currency\CurrencyConverter;
use App\Services\Currency\CurrencyFetcher;
use App\Services\Currency\CurrencyRateUpdater;
use App\Services\Currency\Interfaces\CurrencyConverterInterface;
use App\Services\Currency\Interfaces\CurrencyFetcherInterface;
use App\Services\Currency\Interfaces\CurrencyRateUpdaterInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

class CurrencyRateService
{
    /**
     * @param CurrencyFetcher     $fetcher
     * @param CurrencyRateUpdater $updater
     * @param CurrencyConverter   $converter
     * @param LoggerInterface     $logger,
 */
    public function __construct(
        protected CurrencyFetcherInterface $fetcher,
        protected CurrencyRateUpdaterInterface $updater,
        protected CurrencyConverterInterface $converter,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function updateRates(): void
    {
        try {
            $raw = $this->fetcher->fetch();
            $this->updater->update($raw);
        } catch (Throwable $e) {
            $errorMessage = safe_trans('messages.currency_updated_fail', ['error' => $e->getMessage()]);
            $this->logger->error($errorMessage);
            throw new Exception(safe_trans('messages.currency_rate_update_error'));
        }
    }

    /**
     * @return array<string, float>
     */
    public function getRates(): array
    {
        $rates = $this->converter->getRates();

        return $rates ?? [];
    }

    /**
     * @param float  $amount
     * @param string $toCurrency
     *
     * @return float|null
     */
    public function convertCurrency(float $amount, string $toCurrency): ?float
    {
        return $this->converter->convert($amount, $toCurrency);
    }

    /**
     * @param float         $amount
     * @param array<string> $currencies
     *
     * @return array<string, float|null>
     */
    public function getConvertedPrices(float $amount, array $currencies): array
    {
        return $this->converter->convertMany($amount, $currencies);
    }
}
