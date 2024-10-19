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

        $discountPrice = $this->getDiscountPrice();
        $discount = !$discountPrice ? null : [
            'price' => $discountPrice,
            'date' => $this->discount_end
        ];

        return [
            'title' => $this->title,
            'description' => $this->description,
            'weight' => $this->weight,
            'price' => $this->getPrice(),
            'discount' => $discount,
            'categories' => $this->categories->pluck('title'),
            'images' => $this->getImages()
        ];
    }
}
