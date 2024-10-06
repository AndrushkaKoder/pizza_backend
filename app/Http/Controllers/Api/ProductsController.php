<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductsResource;
use App\Http\Services\ProductsService;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsController extends Controller
{
    public function __construct(private readonly ProductsService $productsService)
    {
    }

    public function index(): JsonResource
    {
        return ProductsResource::collection($this->productsService->getAllProducts());
    }
}
