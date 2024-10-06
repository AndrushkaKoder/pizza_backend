<?php

namespace App\Http\Resources\Product;

use App\Models\CartItems;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsInCartResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /*** @var CartItems $this */

        return [
            'quantity' => $this->quantity,
            'total_product_price' => $this->price,
            'title' => $this->product->title,
            'description' => $this->product->desription,
            'weight' => $this->product->weight,
            'price' => intval($this->product->price),
            'type' => $this->product->type->title,
            'images' => $this->product->getImages()
        ];
    }
}