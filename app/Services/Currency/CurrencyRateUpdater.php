<?php

declare(strict_types=1);

namespace App\Services\Currency;

use App\Repositories\CurrencyRate\Interfaces\CurrencyRateRepositoryInterface;
use App\Services\Currency\Interfaces\CurrencyRateUpdaterInterface;
use Exception;
use Psr\Log\LoggerInterface;

class CurrencyRateUpdater implements CurrencyRateUpdaterInterface
{
    /**
     * @const ALLOWED_CURRENCIES
     */
    private const ALLOWED_CURRENCIES = ['USD', 'RUB', 'EUR'];

    /**
     * @param CurrencyRateParser              $parser
     * @param LoggerInterface                 $logger
     * @param CurrencyRateRepositoryInterface $currencyRateRepository ,
     */
    public function __construct(
        private readonly CurrencyRateParser              $parser,
        protected LoggerInterface                        $logger,
        private readonly CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $rates
     *
     * @return void
     */
    public function update(array $rates): void
    {
        try {
            $filials = $this->parser->parseFilials($rates);

            foreach ($filials as $filial) {
                foreach ($filial['values'] as $value) {
                    if (isset($value['currency'], $value['price']) && is_numeric($value['price'])) {
                        $currencyRateData = new CurrencyRateData(
                            currency: (string)$value['currency'],
                            rate: (float)$value['price'],
                        );

                        $this->processCurrencyRate($currencyRateData);
                    } else {
                        $this->logger->warning(safe_trans('message.error_currency') . json_encode($value));
                    }
                }
            }
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.missing_filials') . $e->getMessage());
        }
    }

    /**
     * @param CurrencyRateData $value
     *
     * @return void
     */
    private function processCurrencyRate(CurrencyRateData $value): void
    {
        if (in_array($value->currency, self::ALLOWED_CURRENCIES, true)) {
            $this->currencyRateRepository->updateOrCreate(
                ['currency' => $value->currency],
                ['rate' => $value->rate]
            );
        } else {
            $this->logger->warning(safe_trans('message.error_currency') . json_encode([
                    'currency' => $value->currency,
                    'rate'     => $value->rate,
                ]));
        }
    }
}
