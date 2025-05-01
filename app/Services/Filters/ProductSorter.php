<?php

declare(strict_types=1);

namespace App\Services\Filters;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductSorter
{
    /**
     * @const DEFAULT_FIELD
     */
    private const DEFAULT_FIELD = 'name';
    /**
     * @const SORT_DIRECTIONS
     */
    private const SORT_DIRECTIONS = [
        'default' => 'asc',
        'reverse' => 'desc',
    ];

    /**
     * @param Builder<Product>                          $query
     * @param array{field?: string, direction?: string} $sortArray
     *
     * @return Builder<Product>
     */
    public function applySort(Builder $query, array $sortArray): Builder
    {
        $field     = $sortArray['field'] ?? self::DEFAULT_FIELD;
        $direction = $this->resolveDirection($sortArray['direction'] ?? null);

        /**
         * @var Builder<Product> $sortedQuery
         */
        $sortedQuery = $query->orderBy($field, $direction);

        return $sortedQuery;
    }

    /**
     * @param string|null $direction
     *
     * @return string
     */
    private function resolveDirection(?string $direction): string
    {
        return in_array($direction, self::SORT_DIRECTIONS, true)
            ? $direction
            : self::SORT_DIRECTIONS['default'];
    }
}
