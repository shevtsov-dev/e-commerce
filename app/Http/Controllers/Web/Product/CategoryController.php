<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Traits\SuccessMessageTrait;
use App\Models\Category;
use App\Repositories\Product\Interfaces\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Psr\Log\LoggerInterface;

class CategoryController extends Controller
{
    use SuccessMessageTrait;

    /**
     * @return string
     */
    protected function entityKey(): string
    {
        return 'category';
    }

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryService             $categoryService
     * @param LoggerInterface             $logger
     */
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected CategoryService             $categoryService,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('categories.index');
    }

    /**
     * @param int $categoryId
     *
     * @return View
     */
    public function show(int $categoryId): View
    {
        return view('categories.show', [
            'category' => $this->categoryRepository
                ->getProductsByCategory($categoryId),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * @param CategoryRequest $request
     *
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->categoryRepository
            ->create($request->validated());

        return redirect(route('categories.index'))
            ->with('success', $this->successMessage('create'));
    }

    /**
     * @param int $categoryId
     *
     * @return View
     */
    public function edit(int $categoryId): View
    {
        return view('categories.edit', [
            'category' => $this->findOrFail($categoryId),
        ]);
    }

    /**
     * @param CategoryRequest $request
     * @param int             $categoryId
     *
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, int $categoryId): RedirectResponse
    {
        $this->categoryService
            ->updateCategory($request, $categoryId);

        return redirect(route('categories.index'))
            ->with('success', $this->successMessage('update'));
    }

    /**
     * @param int $categoryId
     *
     * @return RedirectResponse
     */
    public function destroy(int $categoryId): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($categoryId);

            return redirect(route('categories.index'))
                ->with('success', $this->successMessage('delete'));
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.delete_failed', ['error' => $e->getMessage()]));

            return back()->withErrors(safe_trans('messages.delete_failed'));
        }
    }

    /**
     * @param int $categoryId
     *
     * @return Category
     */
    private function findOrFail(int $categoryId): Category
    {
        return $this->categoryRepository->findById($categoryId);
    }
}
