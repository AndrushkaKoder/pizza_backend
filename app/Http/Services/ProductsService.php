<?php

namespace App\Http\Services;

use App\Http\Resources\Product\ProductsResource;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductsService
{

    public function getProducts(Request $request): AnonymousResourceCollection
    {
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

        return ProductsResource::collection($products);
    }

    public function getProduct(int $id): ProductsResource
    {
        if (Cache::has("product:{$id}")) {
            return new ProductsResource(Cache::get("product:{$id}"));
        }

        $product = Product::query()
            ->isActive()
            ->hasPrice()
            ->whereHas('attachments')
            ->findOrFail($id);

        Cache::set("product:{$id}", $product);

        return new ProductsResource($product);
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
