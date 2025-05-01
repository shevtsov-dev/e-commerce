<?php

declare(strict_types=1);

namespace App\Repositories\Product\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, Category>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $categoryId
     *
     * @return Category
     */
    public function findById(int $categoryId): Category;

    /**
     * @param int $categoryId
     *
     * @return Category
     */
    public function getProductsByCategory(int $categoryId): Category;

    /**
     * @param array<string, mixed> $data
     *
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Category
     */
    public function update(int $id, array $data): Category;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;
}
