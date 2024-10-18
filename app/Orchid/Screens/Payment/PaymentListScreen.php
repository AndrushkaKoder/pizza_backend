<?php

namespace App\Orchid\Screens\Payment;

use App\Models\Payment;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PaymentListScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'payments' => Payment::query()
                ->orderBy('id','desc')
                ->get()
        ];
    }

    public function name(): string
    {
        return 'Типы платежей';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Создать тип')->route('platform.payments.edit')->icon('bs.plus-circle'),
            Link::make('Назад')->route('platform.index')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('payments', [
                TD::make('id', 'id')->render(function (Payment $payment) {
                    return Link::make($payment->id)->route('platform.payments.edit', $payment);
                }),
                TD::make('title', 'Название'),
                TD::make('active', 'Активность')->render(function (Payment $payment) {
                    return $payment->active ? 'Вкл' : 'Выкл';
                })
            ])
        ];
    }
}
