<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\CartItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * @return JsonResponse
     */
    public function getCart(): JsonResponse
    {
        return response()->json($this->cartService->getCart());
    }

    public function addToCart(int $productId): JsonResponse
    {
        $result = $this->cartService->addProductToCart($productId);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ], $result['status']);
    }

    /**
     * @param CartItems $cartItem
     * @return JsonResponse
     */
    public function decrement(CartItems $cartItem): JsonResponse
    {
        $cartItem->update([
            'quantity' => $cartItem->quantity - 1,
            'price' => $cartItem->price - $cartItem->product->price
        ]);

        Auth::user()->cart->decreaseTotalSum(intval($cartItem->product->price));

        if (!$cartItem->quantity) $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'product was decreased'
        ]);
    }

    /**
     * @param CartItems $cartItem
     * @return JsonResponse
     */
    public function delete(CartItems $cartItem): JsonResponse
    {
        Auth::user()->cart->deleteItem($cartItem);

        return response()->json([
            'success' => true,
            'message' => 'product was deleted'
        ]);
    }

}
