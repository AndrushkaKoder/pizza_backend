<?php

namespace App\Orchid\Layouts\Order;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class OrderListLayout extends Table
{

    protected $target = 'orders';

    protected function columns(): iterable
    {
        return [
            TD::make('id','id')
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

            TD::make('updated_at', 'Обновлен')
                ->align('center')
                ->render(function ($order) {
                    return $order->updated_at;
                }),






//            TD::make('first_name', 'First Name')
//                ->sort()
//                ->render(function ($patient) {
//                    return Link::make($patient->first_name)
//                        ->route('platform.clinic.patient.edit', $patient);
//                }),
//
//            TD::make('email','Email'),
//            TD::make('created_at','Date of publication'),
        ];
    }
}
