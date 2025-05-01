<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Producer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @var class-string<Product>
     */
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::query()->inRandomOrder()->first();
        $producer = Producer::query()->inRandomOrder()->first();

        return [
            'name'            => $this->faker->word,
            'alias'           => $this->faker->slug,
            'category_id'     => $category->id ?? 1,
            'producer_id'     => $producer->id ?? 1,
            'description'     => $this->faker->paragraph,
            'price'           => $this->faker->randomFloat(2, 10, 9999),
            'production_date' => $this->faker->date(),
        ];
    }
}
