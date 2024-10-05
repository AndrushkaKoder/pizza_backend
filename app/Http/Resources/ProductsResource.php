<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /**
         * @var Product $this
         */
        return [
            'title' => $this->title,
            'description' => $this->desription,
            'weight' => $this->weight,
            'price' => $this->price(),
            'type' => $this->type->title,
            'images' => $this->getImages()
        ];
    }
}
