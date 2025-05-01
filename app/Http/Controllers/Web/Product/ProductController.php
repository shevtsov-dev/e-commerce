<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Traits\SuccessMessageTrait;
use App\Services\CurrencyRateService;
use App\Services\ProductCsvExporterToQueue;
use App\Services\ProductService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class ProductController extends Controller
{
    use SuccessMessageTrait;

    /**
     * @return string
     */
    protected function entityKey(): string
    {
        return 'product';
    }

    /**
     * @const PAGE_LIMIT
     */
    private const PAGE_LIMIT = 15;

    /**
     * @const FILTER_COLUMNS
     */
    private const FILTER_COLUMNS = ['categories', 'producers', 'price_min', 'price_max'];

    /**
     * @const DEFAULT_SORT_COLUMN_NAME
     */
    private const DEFAULT_SORT_COLUMN_NAME = 'id';

    /**
     * @const DEFAULT_SORT_DIRECTION
     */
    private const DEFAULT_SORT_DIRECTION = 'asc';

    /**
     * @const CURRENCY_KEYS
     */
    private const CURRENCY_KEYS = ['USD', 'EUR', 'RUB'];

    /**
     * @param ProductService            $productService
     * @param ProductCsvExporterToQueue $productExportService
     * @param CurrencyRateService       $currencyRateService
     * @param LoggerInterface           $logger
     */
    public function __construct(
        protected ProductService            $productService,
        protected ProductCsvExporterToQueue $productExportService,
        protected CurrencyRateService       $currencyRateService,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only(self::FILTER_COLUMNS);

        $sortBy    = $request->query('sort_by', self::DEFAULT_SORT_COLUMN_NAME);
        $sortOrder = $request->query('sort_order', self::DEFAULT_SORT_DIRECTION);

        $sortBy    = is_string($sortBy) ? $sortBy : self::DEFAULT_SORT_COLUMN_NAME;
        $sortOrder = is_string($sortOrder) ? $sortOrder : self::DEFAULT_SORT_DIRECTION;

        $products = $this->productService->getFilteredProducts(
            $filters,
            $sortBy,
            $sortOrder,
            self::PAGE_LIMIT,
        );

        return view('products.index', compact('products'));
    }

    /**
     * @param int $productId
     *
     * @return View
     */
    public function show(int $productId): View
    {
        $price = $this->productService->getProductPrice($productId);

        return view('products.show', [
            'product' => $this->productService
                ->getProductById($productId),
            'convertedPrices' => $this->currencyRateService
                ->getConvertedPrices(
                    $price,
                    self::CURRENCY_KEYS,
                )]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * @param ProductRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $this->productService
            ->createProduct($request->validated());

        return redirect(route('products.index'))
            ->with('success', $this->successMessage('create'));
    }

    /**
     * @param int $productId
     *
     * @return View
     */
    public function edit(int $productId): View
    {
        return view('products.edit', [
            'product' => $this->productService
                ->getProductById($productId),
        ]);
    }

    /**
     * @param ProductRequest $request
     * @param int            $productId
     *
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, int $productId): RedirectResponse
    {
        $this->productService
            ->updateProduct(
                $productId,
                $request->validated(),
            );

        return redirect(route('products.index'))
            ->with('success', $this->successMessage('update'));
    }

    /**
     * @param int $productId
     *
     * @return RedirectResponse
     */
    public function destroy(int $productId): RedirectResponse
    {
        try {
            $this->productService->deleteProduct($productId);

            return redirect(route('products.index'))
                ->with('success', $this->successMessage(safe_trans('messages.product.deleted_success')));
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.product.delete_failed' . $e->getMessage()));

            return back()->withErrors(safe_trans('messages.product.delete_failed'));
        }
    }
}
