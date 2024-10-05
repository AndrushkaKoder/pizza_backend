<?php

namespace App\Http\Resources\Baskets;

use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Basket $this
         */
        return [
            'id' => $this->id,
            'products' => $this->products()->get()->toArray()
        ];
    }
}
