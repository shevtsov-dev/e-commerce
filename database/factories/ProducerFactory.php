<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Producer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Producer>
 */
class ProducerFactory extends Factory
{
    /**
     * @var class-string<Producer>
     */
    protected $model = Producer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => $this->faker->company,
            'alias' => $this->faker->slug,
        ];
    }
}
