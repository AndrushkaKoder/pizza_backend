<?php

namespace App\Http\Resources\Product;

use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsInOrder extends JsonResource
{

    public function toArray(Request $request): array
    {
        /**
         * @var OrderItems $this
         */

        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'title' => $this->product->title,
            'price' => $this->product->frontendPrice(),
            'images' => $this->product->getImages()
        ];
    }
}
