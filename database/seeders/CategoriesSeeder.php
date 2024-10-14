<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{

    public function run(): void
    {
        if (!Category::query()->count()) {
            $data = include_once storage_path('seed/categories/categories.php');
            foreach ($data as $category) {
                Category::query()->create($category);
            }
        }
    }
}
