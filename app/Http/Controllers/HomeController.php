<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\Product\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\View\View;

final class HomeController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $products = $this->productRepository->getAll(6);

        return view('home', compact('products'));
    }
}
