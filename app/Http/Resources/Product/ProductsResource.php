<?php

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /*** @var Product $this */

        return [
            'title' => $this->title,
            'description' => $this->description,
            'weight' => $this->weight,
            'price' => $this->frontendPrice(),
            'type' => $this->type->title,
            'images' => $this->getImages()
        ];
    }
}
