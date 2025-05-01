<?php

declare(strict_types=1);

namespace App\Repositories\Product\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, Product>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $id
     *
     * @return Product
     */
    public function findById(int $id): Product;

    /**
     * @param array<string, mixed> $data
     *
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Product
     */
    public function update(int $id, array $data): Product;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator;

    /**
     * @param Builder<Product>     $query
     * @param array<string, mixed> $sortArray
     *
     * @return Builder<Product>
     */
    public function sort(Builder $query, array $sortArray): Builder;

    /**
     * @param array<string, mixed> $filterArray
     *
     * @return Builder<Product>
     */
    public function filter(array $filterArray): Builder;

    /**
     * @param int $id
     *
     * @return float
     */
    public function price(int $id): float;
}
