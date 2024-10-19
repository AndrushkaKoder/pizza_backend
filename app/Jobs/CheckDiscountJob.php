<?php

namespace App\Jobs;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $now = Carbon::now();

        Product::query()
            ->where('discount_active', true)
            ->whereNotNull('discount_end')
            ->get()
            ->each(function (Product $product) use ($now) {
                if (Carbon::parse($product->discount_date) < $now) {
                    $product->update(['discount_active' => false]);
                }
            });
    }
}
