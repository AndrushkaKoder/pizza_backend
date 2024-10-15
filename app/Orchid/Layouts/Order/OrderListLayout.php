<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Order;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{

    protected $target = 'orders';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'id')
                ->align('center')
                ->width('100px')
                ->render(function ($order) {
                    return Link::make($order->id)
                        ->route('platform.orders.edit', $order);
                }),

            TD::make('total_sum', 'Сумма')
                ->align('center')
                ->render(function ($order) {
                    return $order->total_sum;
                }),

            TD::make('address', 'Адрес')
                ->align('center')
                ->render(function ($order) {
                    return $order->address;
                }),

            TD::make('phone', 'Телефон')
                ->align('center')
                ->render(function ($order) {
                    return $order->phone;
                }),


            TD::make('closed', 'Активен')
                ->align('center')
                ->render(function ($order) {
                    return $order->closed ? 'Не активен' : 'Активен';
                }),

            TD::make('created_at', 'Создан')
                ->align('center')
                ->render(function ($order) {
                    return $order->created_at;
                }),

            TD::make('status', 'Статус')
                ->render(function ($order) {
                    return $order->status->title;
                }),

            TD::make('payment', 'Тип оплаты')
                ->render(function ($order) {
                    return $order->payment->title;
                })

        ];
    }

}
