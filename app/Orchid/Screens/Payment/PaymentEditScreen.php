<?php

namespace App\Orchid\Screens\Payment;

use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PaymentEditScreen extends Screen
{

    public $payment;

    public function query(Payment $payment): iterable
    {
        return [
            'payment' => $payment
        ];
    }

    public function name(): string
    {
        return $this->payment->exists ? 'Изменить тип' : 'Добавить тип';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->route('platform.payments.list')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('payment.title')
                        ->title('Название')
                        ->required(),
                    CheckBox::make('payment.active')
                        ->title('Активность')
                ]),
                Button::make($this->payment->exists ? 'Обновить' : 'Добавить')
                    ->method($this->payment->exists ? 'edit' : 'create')
            ])
        ];
    }

    public function edit(Request $request, Payment $payment): RedirectResponse
    {
        $payment->update([
            'title' => $request->input('payment.title'),
            'active' => boolval($request->input('payment.active'))
        ]);

        Toast::info('Тип успешно сохранен');
        return redirect()->route('platform.payments.list');
    }

    public function create(Request $request): RedirectResponse
    {
        $payment = new Payment();
        $payment->fill([
            'title' => $request->input('payment.title'),
            'active' => boolval($request->input('payment.active'))
        ]);
        $payment->save();
        Toast::info('Тип успешно добавлен');
        return redirect()->route('platform.payments.list');
    }
}
