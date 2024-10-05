<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Baskets\BasketResource;
use App\Http\Resources\Orders\OrderResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var User|Authenticatable $this
         */
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'basket' => new BasketResource($this->basket),
            'orders' => OrderResource::collection(
                $this->orders()
                    ->with(['user', 'status'])
                    ->get()
            )
        ];
    }
}
