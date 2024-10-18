<?php

namespace App\Orchid\Screens\Status;

use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;


class StatusEditScreen extends Screen
{
    public $status;

    public function query(Status $status): iterable
    {
        return [
            'status' => $status
        ];
    }

    public function name(): string
    {
        return $this->status->exists ? 'Обновить статус' : 'Добавить статус';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->route('platform.statuses.list')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('status.title')->title('Название'),
                Button::make($this->status->exists ? 'Обновить' : 'Добавить')
                ->method($this->status->exists ? 'edit' : 'create')
            ])
        ];
    }

    public function edit(Request $request, Status $status): RedirectResponse
    {
        $status->update($request->input('status'));
        Toast::info('Статус успешно обновлен');
        return redirect()->route('platform.statuses.list');
    }

    public function create(Request $request): RedirectResponse
    {
        $status = new Status();
        $status->fill($request->input('status'));
        $status->save();
        Toast::info('Статус успешно добавлен');
        return redirect()->route('platform.statuses.list');
    }

}
