<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductService>
 */
class ProductServiceFactory extends Factory
{
    /**
     * @var class-string<ProductService>
     */
    protected $model = ProductService::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::query()->inRandomOrder()->first();
        $service = Service::query()->inRandomOrder()->first();

        return [
            'product_id' => $product->id ?? 1,
            'service_id' => $service->id ?? 1,
        ];
    }
}
