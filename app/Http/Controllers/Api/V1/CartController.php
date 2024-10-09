<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\CartItems;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{

    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->cartService->getCart();
    }

    public function addToCart(Product $product): JsonResponse
    {
        return $this->cartService->addProductToCart($product);
    }

    public function decrement(CartItems $cartItem): JsonResponse
    {
        return $this->cartService->decrementExistsProduct($cartItem);
    }

    public function delete(CartItems $cartItem): JsonResponse
    {
        return $this->cartService->deleteProductFromCart($cartItem);
    }

}
