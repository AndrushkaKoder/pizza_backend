<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductsResource;
use App\Http\Services\GetProductsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsController extends Controller
{
    public function __construct(private readonly GetProductsService $productsService)
    {
    }

    public function index(Request $request): JsonResource
    {
        $products = $this->productsService->getProducts();
        return ProductsResource::collection($this->filter($request, $products));
    }

    public function show(int $id): ProductsResource
    {
        return new ProductsResource($this->productsService->getProduct($id));
    }

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
