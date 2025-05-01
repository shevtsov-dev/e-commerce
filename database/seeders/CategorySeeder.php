<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        Category::factory()->count(5)->create();
    }
}
