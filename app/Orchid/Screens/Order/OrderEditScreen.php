<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use Orchid\Screen\Screen;

class OrderEditScreen extends Screen
{

    public $order;

    public function query(Order $order): iterable
    {
        return [
            'order' => $order
        ];
    }

    public function layout(): iterable
    {
        return [];
    }
}
