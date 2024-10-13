<?php

declare(strict_types=1);

namespace App\Http\Services\ApiService;

use App\Http\Resources\Product\ProductsResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class ProductsService
{

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * Получение всех товаров
     */
    public function getProducts(Request $request): AnonymousResourceCollection
    {
        $products = Cache::remember(Product::CACHE_NAME, Product::CACHE_TTL, function () {
            return Product::query()
                ->isActive()
                ->hasPrice()
                ->orderDesc()
                ->with(['categories', 'attachments'])
                ->whereHas('attachments')
                ->get();
        });

        return ProductsResource::collection($this->filter($request, $products));
    }

    /**
     * @param int $id
     * @return ProductsResource
     * Получение товара по id (id - потому что берем из кеша)
     */
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

    /**
     * @param Request $request
     * @param Collection $products
     * @return Collection
     * Фильтр коллекции товаров по входным параметрам
     */
    protected function filter(Request $request, Collection $products): Collection
    {
        if ($order = $request->input('order')) {
            $products = $order === 'asc' ? $products->sortBy('price') : $products->sortByDesc('price');
        }

        if ($type = $request->input('category')) {
            $products = $products->filter(function ($item) use ($type) {
                return $item->categories->where('id', $type)->count();
            });
        }

        return $products;
    }
}
