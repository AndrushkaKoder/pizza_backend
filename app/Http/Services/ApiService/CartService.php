<?php

declare(strict_types=1);

namespace App\Http\Services\ApiService;

use App\Http\Resources\Product\ProductsInCartResource;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartService
{

    /**
     * @param Product $product
     * @return JsonResponse
     * Добавление товара в корзину
     */
    public function addProductToCart(Product $product): JsonResponse
    {
        if (!$product->priceInteger() || !$product->active()) {
            return response()->json([
                'success' => false,
                'message' => 'Product not allowed'
            ], 400);
        }

        $user = Auth::user();

        /**
         * @var User $user
         * @var Cart $cart
         */

        $cart = $user->cart()->firstOrCreate();

        if ($existsProduct = $cart->items->where('product_id', $product->id)->first()) {

            foreach ($existsProduct->product->categories as $category) {
                if (!$category->canAddMore($existsProduct->quantity)) {
                    return response()->json([
                        'success' => false,
                        'message' => "You can`t add more products of {$category->title}. Max quantity is {$category->max_for_order}"
                    ], 400);
                }
            }

            $existsProduct->update([
                'quantity' => $existsProduct->quantity + 1,
                'price' => $existsProduct->price + $product->priceInteger()
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'price' => $product->priceInteger()
            ]);
        }

        $cart->increaseTotalSum(intval($product->price));

        return response()->json([
            'success' => true,
            'message' => 'add success'
        ]);
    }

    /**
     * @return JsonResponse
     * Получение корзины
     */
    public function getCart(): JsonResponse
    {
        $user = Auth::user();

        /**
         * @var User $user ;
         */

        $cartItems = $user->cart?->items;

        if (!$cartItems) return response()->json([
            'message' => 'cart empty',
        ]);

        $total = [
            'data' => [
                'total_price' => $user->cart->total_sum,
                'quantity' => $cartItems->count()
            ]
        ];

        $total['data']['products'] = ProductsInCartResource::collection($cartItems);

        return response()->json($total['data']['total_price'] > 0 ? $total : ['message' => 'cart empty']);
    }

    /**
     * @param CartItems $cartItem
     * @return JsonResponse
     * Декремент товара в корзине
     */
    public function decrementExistsProduct(CartItems $cartItem): JsonResponse
    {
        $cartItem->update([
            'quantity' => $cartItem->quantity - 1,
            'price' => $cartItem->price - $cartItem->product->price
        ]);

        Auth::user()->cart->decreaseTotalSum(intval($cartItem->product->price));

        if (!$cartItem->quantity) $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'product was deleted'
        ]);
    }

    /**
     * @param CartItems $cartItem
     * @return JsonResponse
     * Удаление товара из корзины
     */
    public function deleteProductFromCart(CartItems $cartItem): JsonResponse
    {
        Auth::user()->cart->decreaseTotalSum($cartItem->product->price);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'product was deleted'
        ]);
    }

}
