<?php

namespace App\Http\Services;

use App\Http\Middleware\Authenticate;
use App\Http\Requests\Order\CreateOrder;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrdersService
{
    public function createNewOrder(CreateOrder $request): JsonResponse
    {
        $user = Auth::user();
        if ($user?->cart) {
            $order = new Order();
            $order->fill([
                'user_id' => Auth::id(),
                'status_id' => Status::JUST_CREATED,
                'payment_id' => $request->validated('payment_id'),
                'delivery_time' => Carbon::parse($request->validated('delivery_time'))->format('d-m-y H:i:s'),
                'address' => $request->validated('address'),
                'phone' => $request->validated('phone'),
                'closed' => false,
                'total_sum' => $user->cart->total_sum,
            ]);
            $order->save();

            foreach ($user->cart?->items as $cartProduct) {
                $order->items()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->id,
                    'quantity' => $cartProduct->quantity
                ]);
            }

            $this->setDefaultDataForUser($user, 'phone', $request->validated('phone'));
            $this->setDefaultDataForUser($user, 'default_address', $request->validated('address'));

            $user->cart()->first()->delete();

            return response()->json([
                'success' => true,
                'message' => 'new order was created'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'order has not been created'
        ], 400);

    }

    private function setDefaultDataForUser(User|Authenticate $user, string $key, string $value): void
    {
        if (!$user->$key) {
            $user->update([
                $key => $value
            ]);
        }
    }

}
