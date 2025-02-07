<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        if (app()->isProduction()) return;

        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
