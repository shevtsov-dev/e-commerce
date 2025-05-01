<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        Product::factory()->count(100)->create();
    }
}
