<?php

namespace App\Http\Services;

use App\Http\Resources\Product\ProductsResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductsService
{

    public function getProducts(Request $request): AnonymousResourceCollection
    {
        Cache::flush();
        $products = Cache::remember(Product::CACHE_NAME, Product::CACHE_TTL, function () {
            return Product::query()
                ->isActive()
                ->hasPrice()
                ->orderDesc()
                ->with(['categories', 'attachments'])
                ->whereHas('attachments')
                ->get();
        });

        return ProductsResource::collection($this->filter($request,$products));
    }

    public function getProduct(int $id): ProductsResource
    {
        if (Cache::has("product:{$id}")) {
            return new ProductsResource(Cache::get("product:{$id}"));
        }

        $product = Product::query()
            ->isActive()
            ->hasPrice()
            ->hasImages()
            ->with('attachments')
            ->findOrFail($id);

        Cache::set("product:{$id}", $product);

        return new ProductsResource($product);
    }

    protected function filter(Request $request, Collection $products): Collection
    {
        if ($order = $request->input('sort')) {
            $products = $order === 'asc' ? $products->sortBy('price') : $products->sortByDesc('price');
        }

        if ($type = $request->input('category')) {
        }

        return $products;
    }
}
