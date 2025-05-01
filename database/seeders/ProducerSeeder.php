<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Producer;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        Producer::factory()->count(5)->create();
    }
}
