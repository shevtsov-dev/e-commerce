<?php

declare(strict_types=1);

namespace App\Formatters\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface DataFormatterInterface
{
    /**
     * @param Collection<int, Product> $products
     *
     * @return string
     */
    public function format(Collection $products): string;
}
