<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ProductListScreen extends Screen
{

    public function query(): iterable
    {
        return [
            'products' => Product::query()
                ->filters()
                ->with(['attachments', 'categories'])
                ->orderBy('id', 'desc')
                ->paginate(25)
        ];
    }

    public function name(): string
    {
        return 'Товары';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Добавить товар')->route('platform.products.edit')->icon('bs.plus-circle'),
            Link::make('Назад')->route('platform.index')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('products', [
                TD::make('id', 'id')->sort(),
                TD::make('attachments', 'Изображение')->render(function (Product $product) {
                    $img = $product->attachments()->first()?->url();
                    return $img ? "<img src={$img} style='width: 50px' alt='product'>" : '';
                }),

                TD::make('title', 'Название'),

                TD::make('price', 'Цена')->sort(),

                TD::make('active', 'Активность')
                    ->render(function (Product $product) {
                        return $product->active ? 'Вкл' : 'Выкл';
                    }),

                TD::make('created_at', 'Дата создания')->render(function (Product $product) {
                    $date = new \DateTime($product->created_at);
                    return $date->format('Y-m-d h:i:s');
                }),

                TD::make('action', 'Редактировать')->render(function (Product $product) {
                    return Link::make('')
                        ->icon('bs.pencil-fill')
                        ->route('platform.products.edit', $product);
                })->align('center')

            ])
        ];
    }

}
