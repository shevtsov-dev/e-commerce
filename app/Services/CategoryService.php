<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\Product\Interfaces\CategoryRepositoryInterface;
use Psr\Log\LoggerInterface;

class CategoryService
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param LoggerInterface             $logger
     */
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param CategoryRequest $request
     * @param int             $categoryId
     *
     * @return Category
     */
    public function updateCategory(CategoryRequest $request, int $categoryId): Category
    {
        $category = $this->categoryRepository->findById($categoryId);

        $category->update($request->validated());
        $this->logger->info(safe_trans('messages.updated_success'. $category->id));

        return $category;
    }

    /**
     * @param int $categoryId
     *
     * @return void
     */
    public function deleteCategory(int $categoryId): void
    {
        $category = $this->categoryRepository
            ->findById($categoryId);
        $category->delete();
        $this->logger->info(safe_trans('messages.deleted_success'. $category->id));
    }
}
