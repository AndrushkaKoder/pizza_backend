<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{

    public function run(): void
    {
        if (app()->isProduction()) return;

        if (!Status::query()->count()) {
            $data = include_once storage_path('seed/statuses/statuses.php');
            foreach ($data as $status) {
                Status::query()->create($status);
            }
        }
    }
}
