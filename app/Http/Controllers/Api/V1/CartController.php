<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductsInCartResource;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    /**
     * @return JsonResponse
     * Получить корзину текущего пользователя
     */
    public function getCart(): JsonResponse
    {
        $user = Auth::user();

        /**@var User|Authenticatable $user */

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

    /**
     * @param int $productId
     * @return JsonResponse
     * Добавить товар в корзину
     */
    public function addToCart(int $productId): JsonResponse
    {
        $product = Product::query()->findOrFail($productId);
        if (!$product->getPrice() || !$product->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Product not allowed',
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
                        'message' => "You can`t add more products of {$category->title}. Max quantity is {$category->max_for_order}",
                    ], 400);
                }
            }

            $existsProduct->update([
                'quantity' => $existsProduct->quantity + 1,
                'price' => $existsProduct->price + $product->getPrice()
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'price' => $product->getPrice()
            ]);
        }

        $cart->increaseTotalSum(intval($product->price));

        return response()->json([
            'success' => true,
            'message' => 'success',
        ]);
    }

    /**
     * @param CartItems $cartItem
     * @return JsonResponse
     * Уменьшить кол-во позиций товара в корзине на 1
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
     * Удалить товар из корзины
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
