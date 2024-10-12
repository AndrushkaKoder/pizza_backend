<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductsResource;
use App\Http\Services\ApiService\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsController extends Controller
{
    public function __construct(private readonly ProductsService $productsService)
    {
    }

    public function index(Request $request): JsonResource
    {
        return $this->productsService->getProducts($request);
    }

    public function show(int $id): ProductsResource
    {
        return $this->productsService->getProduct($id);
    }
}
