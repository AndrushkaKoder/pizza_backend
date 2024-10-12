<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemsResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'quantity' => $this->quantity,
            'products' => new ProductsResource($this->product)
        ];
    }
}
