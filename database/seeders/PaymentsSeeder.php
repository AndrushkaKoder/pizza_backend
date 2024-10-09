<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{

    public function run(): void
    {
        if (!Payment::query()->count()) {
            $data = include_once storage_path('seed/payments/payments.php');
            foreach ($data as $item) {
                Payment::query()->create($item);
            }
        }
    }
}
