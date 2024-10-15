<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class OrderEditScreen extends Screen
{

    public $order;

    public function query(Order $order): iterable
    {
        return [
            'order' => $order
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->route('platform.index')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('user')
                    ->type('text')
                    ->title('Кто заказал')
                    ->value($this->order->user->name),

                Input::make('phone')
                    ->type('text')
                    ->title('Телефон заказчика')
                    ->value($this->order->phone),

                TextArea::make('comment')
                    ->rows(5)
                    ->title('Комментарий')
                   ->value($this->order->comment)->hr(),

                Select::make('status')
                    ->title('Статус')
                    ->fromModel(Status::class, 'title')
                    ->required()
                    ->value($this->order->status),

                Select::make('payment')
                    ->title('Тип оплаты')
                    ->fromModel(Payment::class, 'title')
                    ->required()
                    ->value($this->order->payment->id),

                CheckBox::make('closed')
                    ->value($this->order->closed)
                    ->title('Закрыть заказ'),

                Button::make('Сохранить')->method('save')
            ])
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $this->order->update([
            'payment_id' => intval($request->input('payment')),
            'status_id' => intval($request->input('status')),
            'closed' => boolval($request->input('closed'))
        ]);

        Toast::info('Заказ успешно обновлен');
        return redirect()->route('platform.orders')->with('success');
    }
}
