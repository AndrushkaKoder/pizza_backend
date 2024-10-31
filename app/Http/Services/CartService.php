<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartService
{

    /**
     * @param int $productId
     * @return array
     * Добавление товара в корзину
     */
    public function addProductToCart(int $productId): array
    {
        $product = Product::query()->findOrFail($productId);
        if (!$product->getPrice() || !$product->isActive()) {
            return [
                'success' => false,
                'message' => 'Product not allowed',
                'status' => 400
            ];
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
                    return [
                        'success' => false,
                        'message' => "You can`t add more products of {$category->title}. Max quantity is {$category->max_for_order}",
                        'status' => 400
                    ];
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

        return [
            'success' => true,
            'message' => 'success',
            'status' => 200
        ];
    }


}
