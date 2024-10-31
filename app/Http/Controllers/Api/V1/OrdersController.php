<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{

    /**
     * @return ResourceCollection
     * Список заказов пользователя
     */
    public function index(): ResourceCollection
    {
        return Cache::remember(Order::CACHE_NAME, Order::CACHE_TIME, function () {
            $user = Auth::user();
            return OrderResource::collection($user->orders);
        });
    }

    /**
     * @param Order $order
     * @return OrderResource
     * Заказ пользователя
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    /**
     * @param CreateOrder $request
     * @return JsonResponse
     * Создать новый заказ
     */
    public function create(CreateOrder $request): JsonResponse
    {
        $user = Auth::user();

        /**@var User|Authenticatable $user */

        if (!$user->cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart empty'
            ]);
        }

        $created = DB::transaction(function () use ($request, $user) {
            try {
                $order = $user->orders()->create([
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

                $cartItems = $user->cart->items->toArray();
                $order->items()->createMany($cartItems);

                $this->setDefaultDataForUser($user, 'phone', $request->validated('phone'));
                $this->setDefaultDataForUser($user, 'default_address', $request->validated('address'));

                $user->cart()->delete();
                Cache::delete(Order::CACHE_NAME);

                return [
                    'success' => true,
                    'message' => 'success'
                ];

            } catch (\Exception $exception) {
                return [
                    'success' => false,
                    'message' => $exception->getMessage()
                ];
            }
        });

        return response()->json($created, $created['success'] ? 201 : 400);
    }

    /**
     * @param User|Authenticate $user
     * @param string $key
     * @param string $value
     * @return void
     * Установка дефолтных параметров для юзера
     */
    private function setDefaultDataForUser(User|Authenticate $user, string $key, string $value): void
    {
        if (!$user->$key) {
            $user->update([
                $key => $value
            ]);
        }
    }

    /**
     * @param Order $order
     * @param int $statusId
     * @return OrderResource
     * Изменить статус заказа по АПИ (Задел на микросервис)
     */
    public function changeStatus(Order $order, int $statusId): OrderResource
    {
        $status = Status::query()->findOrFail($statusId);
        if ($status) $order->update(['status_id' => $status->id]);
        return new OrderResource($order);
    }

    /**
     * @param Order $order
     * @param int $paymentId
     * @return OrderResource
     * Изменить тип оплаты по АПИ (Возможно для клиента)
     */
    public function changePayment(Order $order, int $paymentId): OrderResource
    {
        $payment = Payment::query()->findOrFail($paymentId);
        if ($payment) $order->update(['payment_id' => $payment->id]);
        return new OrderResource($order);
    }
}
