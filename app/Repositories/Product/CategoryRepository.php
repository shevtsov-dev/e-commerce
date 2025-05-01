<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Models\Category;
use App\Repositories\Product\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @param Category $model
     */
    public function __construct(
        protected Category $model,
    ) {
    }

    /**
     * @param int $categoryId
     *
     * @return Category
     */
    public function findById(int $categoryId): Category
    {
        return $this->query()
            ->findOrFail($categoryId);
    }

    /**
     * @param int $categoryId
     *
     * @return Category
     */
    public function getProductsByCategory(int $categoryId): Category
    {
        return $this->query()
            ->with('products')
            ->findOrFail($categoryId);
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, Category>
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
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($limitPerPage);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Category
     */
    public function create(array $data): Category
    {
        return $this->query()
            ->create($data);
    }

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Category
     */
    public function update(int $id, array $data): Category
    {
        $category = $this->findById($id);
        $category->update($data);

        return $category;
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
     * @return Builder<Category>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
