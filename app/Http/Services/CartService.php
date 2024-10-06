<?php

namespace App\Http\Services;

use App\Http\Resources\Product\ProductsInCartResource;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartService
{

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

            if (!$existsProduct->product->type->canAddMore($existsProduct->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "You can`t add more products of current type. Max quantity is {$existsProduct->product->type->max_count}"
                ], 400);
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

        return response()->json([
            'success' => true,
            'message' => 'add success'
        ]);
    }

    public function getCart(): JsonResponse
    {
        /**@var User $user */

        $user = Auth::user();
        $cartItems = $user->cart->items;

        $total = [
            'total_price' => 0,
            'quantity' => $cartItems->count()
        ];
        foreach ($cartItems as $item) {
            $total['total_price'] += intval($item->price);
            $total['products'][] = new ProductsInCartResource($item);
        }

        return response()->json($total);
    }

    public function decrementExistsProduct(CartItems $cartItem): JsonResponse
    {
        $cartItem->update([
            'quantity' => $cartItem->quantity - 1
        ]);

        if (!$cartItem->quantity) $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'product was deleted'
        ]);
    }

    public function deleteProductFromCart(CartItems $cartItem): JsonResponse
    {
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'product was deleted'
        ]);
    }

}
