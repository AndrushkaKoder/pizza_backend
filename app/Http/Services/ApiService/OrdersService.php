<?php

namespace App\Http\Services\ApiService;

use App\Http\Middleware\Authenticate;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Payment;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OrdersService
{

    /**
     * @return JsonResponse
     * Получить заказы юзера
     */
    public function getUserOrders(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(OrderResource::collection($user->orders));
    }

    /**
     * @param CreateOrder $request
     * @return JsonResponse
     * Создать заказ
     */
    public function createNewOrder(CreateOrder $request): JsonResponse
    {
        $user = Auth::user();

        /** @var User $user */

        if ($user?->cart) {
            $order = new Order();
            $order->fill([
                'user_id' => Auth::id(),
                'status_id' => Status::JUST_CREATED,
                'payment_id' => $request->validated('payment_id'),
                'delivery_time' => Carbon::parse($request->validated('delivery_time'))->format('d-m-y H:i:s'),
                'address' => $request->validated('address'),
                'phone' => $request->validated('phone'),
                'comment' => $request->validated('comment'),
                'closed' => false,
                'total_sum' => $user->cart->items->sum('price'),
            ]);
            $order->save();

            foreach ($user->cart->items as $cartItem) {
                OrderItems::query()->create([
                    'order_id' => $order->id,
                    'product_id'=> $cartItem->product->id,
                    'quantity' => $cartItem->quantity
                ]);
            }

            $this->setDefaultDataForUser($user, 'phone', $request->validated('phone'));
            $this->setDefaultDataForUser($user, 'default_address', $request->validated('address'));


            $user->cart()->delete();
            Cache::delete('orders');

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

    public function changeStatus(Order $order, int $statusId): JsonResponse
    {
        $status = Status::query()->find($statusId);
        if ($status) $order->update(['status_id' => $status->id]);
        return response()->json(new OrderResource($order));
    }

    public function changePayment(Order $order, int $paymentId): JsonResponse
    {
        $payment = Payment::query()->find($paymentId);
        if ($payment) $order->update(['payment_id' => $payment->id]);
        return response()->json(new OrderResource($order));
    }

}
