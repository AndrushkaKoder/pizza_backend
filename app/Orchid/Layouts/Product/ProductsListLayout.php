<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductsListLayout extends Table
{

    protected $target = 'products';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'id')
                ->render(function ($product) {
                    return Link::make($product->id)->route('platform.products.edit', $product);
                }),
            TD::make('title', 'Название')
            ->render(function ($product) {
                return $product->title;
            }),

            TD::make('price', 'Цена')
                ->render(function ($product) {
                    return $product->price . ' P';
                }),

            TD::make('active', 'Активность')
                ->render(function ($product) {
                    return $product->active ? 'Вкл' : 'Выкл';
                })
        ];
    }
}
