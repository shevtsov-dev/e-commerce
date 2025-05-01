<?php

declare(strict_types=1);

namespace App\Services\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    /**
     * @param Builder<Product>     $query
     * @param array<string, mixed> $filterArray
     *
     * @return Builder<Product>
     */
    public function applyFilter(Builder $query, array $filterArray): Builder
    {
        foreach ($filterArray as $filter => $value) {
            if ($this->isEmpty($value)) {
                continue;
            }

            $this->applySingleFilter($query, $filter, $value);
        }

        return $query;
    }

    /**
     * @param Builder<Product> $query
     * @param string           $filter
     * @param mixed            $value
     *
     * @return void
     */
    private function applySingleFilter(Builder $query, string $filter, mixed $value): void
    {
        match ($filter) {
            'categories' => $query->whereIn('category_id', (array) $value),
            'producers'  => $query->whereIn('producer_id', (array) $value),
            'price_min'  => $query->where('price', '>=', $value),
            'price_max'  => $query->where('price', '<=', $value),
            default      => null,
        };
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    private function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '';
    }
}
