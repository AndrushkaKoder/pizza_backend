<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Services\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function __construct(private readonly OrdersService $ordersService)
    {
    }

    public function create(CreateOrder $request): JsonResponse
    {
        return $this->ordersService->createNewOrder($request);
    }
}
