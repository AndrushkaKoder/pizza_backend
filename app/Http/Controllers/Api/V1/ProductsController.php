<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\Products\ProductsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(private readonly ProductsRepository $productsService)
    {
    }

    public function index(Request $request): JsonResponse
    {
       return new JsonResponse( $this->productsService->getProducts());
    }

    public function show(int $id): JsonResponse
    {
        return new JsonResponse($this->productsService->getProduct($id));
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
