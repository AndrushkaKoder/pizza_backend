<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class GetProductsService
{

    /**
     * @return Collection
     * Получение всех товаров
     */
    public function getProducts(): Collection
    {
        return Cache::remember(Product::CACHE_NAME, Product::CACHE_TTL, function () {
            return Product::query()
                ->isActive()
                ->hasPrice()
                ->hasImages()
                ->orderDesc()
                ->with(['categories', 'attachments'])
                ->get();
        });
    }

    /**
     * @param int $id
     * @return Product
     * Получение товара
     */
    public function getProduct(int $id): Product
    {

        if (Cache::has("product:{$id}")) {
            return Cache::get("product:{$id}");
        }

        $product = Product::query()
            ->isActive()
            ->hasPrice()
            ->with('attachments')
            ->findOrFail($id);

        Cache::set("product:{$id}", $product);

        return $product;
    }
}
