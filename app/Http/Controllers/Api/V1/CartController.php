<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductsInCartResource;
use App\Http\Services\ApiService\CartService;
use App\Models\CartItems;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
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
    public function index(): JsonResponse
    {
        $user = Auth::user();
        /**
         * @var User|Authenticatable $user
         */

        $cartItems = $user->cart?->items;

        if (!$cartItems) return response()->json();

        $total = [
            'data' => [
                'total_price' => $user->cart->total_sum,
                'quantity' => $cartItems->sum('quantity')
            ]
        ];

        $total['data']['products'] = ProductsInCartResource::collection($cartItems);

        return response()->json($total['data']['total_price'] > 0 ? $total : []);
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
