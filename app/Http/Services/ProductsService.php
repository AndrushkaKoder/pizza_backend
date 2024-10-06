<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductsService
{

    public function getAllProducts(): Collection
    {
        return Product::query()
            ->isActive()
            ->hasPrice()
            ->with(['type', 'attachment'])
            ->get();
    }

}
