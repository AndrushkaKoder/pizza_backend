<?php

namespace App\Console\Commands;

use App\Jobs\CheckDiscountJob;
use Illuminate\Console\Command;

class checkDiscount extends Command
{

    protected $signature = 'app:check-discount';

    protected $description = 'Check products discounts';

    public function handle(): void
    {
        CheckDiscountJob::dispatch();
    }
}
