<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @var class-string<Service>
     */
    protected $model = Service::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word,
            'alias'       => $this->faker->slug,
            'target_date' => $this->faker->date(),
            'price'       => $this->faker->randomFloat(2, 50, 1000),
        ];
    }
}
