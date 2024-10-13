<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Resources\Order\OrderResource;
use App\Http\Services\ApiService\OrdersService;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersController extends Controller
{

    public function __construct(private readonly OrdersService $ordersService)
    {
    }

    public function index(): ResourceCollection
    {
        return $this->ordersService->getOrders();
    }

    public function show(Order $order): OrderResource
    {
        return $this->ordersService->getOrder($order);
    }

    public function create(CreateOrder $request): JsonResponse
    {
        return $this->ordersService->createNewOrder($request);
    }

    public function changeStatus(Order $order, int $statusId): OrderResource
    {
        return $this->ordersService->changeStatus($order, $statusId);
    }

    public function changePayment(Order $order, int $paymentId): OrderResource
    {
        return $this->ordersService->changePayment($order, $paymentId);
    }
}
