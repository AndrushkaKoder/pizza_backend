<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductsService
{

    public function getAllProducts(Request $request): Collection
    {
        Cache::delete(Product::CACHE_NAME);
        $products = Cache::remember(Product::CACHE_NAME, Product::CACHE_TTL, function () {
            return Product::query()
                ->isActive()
                ->hasPrice()
                ->orderDesc()
                ->with(['type', 'attachments'])
                ->whereHas('attachments')
                ->get();
        });

        if ($order = $request->input('orders')) {
            if ($this->filterParams($order)) {
                $products = $order === 'asc' ? $products->sortBy('price') : $products->sortByDesc('price');
            }
        }

        if ($type = $request->input('type')) {
            if ($this->filterParams($type)) {
                $products = $products->where('type_id', intval($type));
            }
        }

        return $products;
    }

    private function filterParams($param): bool
    {
        return in_array($param, [
            'asc',
            'desc',
            ProductType::T_PIZZA,
            ProductType::T_DRINK,
            ProductType::T_SNACKS
        ]);
    }

}
