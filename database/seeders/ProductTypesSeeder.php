<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypesSeeder extends Seeder
{

    public function run(): void
    {
        if (app()->isProduction()) return;

        if (!ProductType::query()->count()) {
            $data = include_once storage_path('seed/product_types/product_types.php');
            foreach ($data as $typeItem) {
                ProductType::query()->create($typeItem);
            }
        }
    }
}
