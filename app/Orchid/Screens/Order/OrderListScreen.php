<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Orchid\Layouts\Order\OrderListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class OrderListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'orders' => Order::query()
                ->with(['items', 'status', 'payment'])
                ->orderBy('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->route('platform.index')
        ];
    }

    public function name(): string
    {
        return 'Заказы';
    }

    public function layout(): iterable
    {
        return [
          OrderListLayout::class
        ];
    }
}
