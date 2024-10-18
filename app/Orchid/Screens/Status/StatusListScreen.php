<?php

namespace App\Orchid\Screens\Status;

use App\Models\Status;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class StatusListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'statuses' => Status::query()
                ->orderBy('id', 'desc')
                ->get()
        ];
    }

    public function name(): string
    {
        return 'Статусы';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Добавить статус')->route('platform.statuses.edit')->icon('bs.plus-circle'),
            Link::make('Назад')->route('platform.index')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('statuses', [
                TD::make('id', 'id')->render(function (Status $status) {
                    return Link::make($status->id)->route('platform.statuses.edit', $status);
                }),
                TD::make('title', 'Название')
            ])
        ];
    }

}
