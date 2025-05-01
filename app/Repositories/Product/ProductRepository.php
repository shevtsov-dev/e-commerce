<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\Interfaces\ProductRepositoryInterface;
use App\Services\Filters\ProductFilter;
use App\Services\Filters\ProductSorter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @param Product       $model
     * @param ProductFilter $productFilter
     * @param ProductSorter $productSorter
     */
    public function __construct(
        protected Product $model,
        protected ProductFilter $productFilter,
        protected ProductSorter $productSorter,
    ) {
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, Product>
     */
    public function getAll(?int $limit = null): Collection
    {
        $query = $this->query();

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param int $id
     *
     * @return Product
     */
    public function findById(int $id): Product
    {
        return $this->query()
            ->findOrFail($id);
    }

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator
    {
        return $this->query()
            ->with('category', 'producer')
            ->paginate($limitPerPage);
    }

    /**
     * @param array<string, mixed> $filterArray
     *
     * @return Builder<Product>
     */
    public function filter(array $filterArray): Builder
    {
        $query = $this->query();

        return $this->productFilter->applyFilter($query, $filterArray);
    }

    /**
     * @param Builder<Product>     $query
     * @param array<string, mixed> $sortArray
     *
     * @return Builder<Product>
     */
    public function sort(Builder $query, array $sortArray): Builder
    {
        return $this->productSorter->applySort($query, $sortArray);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Product
     */
    public function create(array $data): Product
    {
        return $this->query()
            ->create($data);
    }

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Product
     */
    public function update(int $id, array $data): Product
    {
        $product = $this->findById($id);
        $product->update($data);

        return $product;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) $this->query()
            ->find($id)
            ?->delete();
    }

    /**
     * @param int $id
     *
     * @return float
     */
    public function price(int $id): float
    {
        $price = $this->query()
           ->where('id', $id)
           ->value('price');

        return (float) $price;
    }

    /**
     * @return Builder<Product>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
