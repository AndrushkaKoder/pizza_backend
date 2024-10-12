<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Services\ApiService\OrdersService;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{

    public function __construct(private readonly OrdersService $ordersService)
    {
    }

    public function index(): JsonResponse
    {
        return $this->ordersService->getUserOrders();
    }

    public function create(CreateOrder $request): JsonResponse
    {
        return $this->ordersService->createNewOrder($request);
    }

    public function changeStatus(Order $order, int $statusId): JsonResponse
    {
        return $this->ordersService->changeStatus($order, $statusId);
    }

    public function changePayment(Order $order, int $paymentId): JsonResponse
    {
        return $this->ordersService->changePayment($order, $paymentId);
    }
}
