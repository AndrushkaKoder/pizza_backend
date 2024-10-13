<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\ProductsInOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /**
         * @var Order $this
         */

        return [
            'id' => $this->id,
            'sum' => $this->total_sum,
            'address' => $this->address,
            'phone' => $this->phone,
            'comment' => $this->comment,
            'delivery_time' => $this->delivery_time,
            'status' => $this->status->title,
            'payment' => $this->payment->title,
            'closed' => $this->closed,
            'products' => ProductsInOrder::collection($this->items)
        ];
    }
}
