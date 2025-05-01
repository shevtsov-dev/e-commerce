<?php

declare(strict_types=1);

namespace App\Services\Currency\Interfaces;

interface CurrencyFetcherInterface
{
    /**
     * @return array<string, mixed>
     */
    public function fetch(): array;
}
