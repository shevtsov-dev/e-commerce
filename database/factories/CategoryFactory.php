<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @var class-string<Category>
     */
    protected $model = Category::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::query()->inRandomOrder()->first();

        return [
            'name'      => $this->faker->word,
            'alias'     => $this->faker->slug,
            'parent_id' => $category->id ?? 1,
        ];
    }
}
