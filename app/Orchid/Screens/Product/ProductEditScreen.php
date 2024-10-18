<?php

namespace App\Orchid\Screens\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductEditScreen extends Screen
{

    public $product;

    public function query(Product $product): iterable
    {
        $product->load('attachments');
        return [
            'product' => $product
        ];
    }

    public function name(): string
    {
        return $this->product->exists ? $this->product->title : 'Добавить товар';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->route('platform.products.list')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('product.title')
                        ->title('Название')
                        ->required(),

                    Input::make('product.price')
                        ->type('number')
                        ->title('Цена')
                        ->max(10000)
                        ->required(),

                    Input::make('product.weight')
                        ->type('integer')
                        ->max(10000)
                        ->title('Вес')
                ]),

                Group::make([
                    Select::make('product.categories')
                        ->title('Категория')
                        ->fromModel(Category::class, 'title')
                        ->multiple()
                        ->required(),

                    CheckBox::make('product.active')
                        ->title('Активность')
                ]),

                TextArea::make('product.description')
                    ->title('Описание товара')
                    ->rows(5),

                Upload::make('product.attachments')
                    ->acceptedFiles('image/*,application/pdf,.psd')
                    ->help('Изображения товара (3 максимум)')
                    ->maxFileSize(10)
                    ->maxFiles(3)
                    ->groups('product'),

                Button::make($this->product->exists ? 'Сохранить' : 'Добавить')
                    ->method($this->product->exists ? 'edit' : 'create')
            ])
        ];
    }

    public function edit(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->getData($request));
        $product->categories()->sync($request->input('product.categories'));

        $product->attachments('product')->syncWithoutDetaching(
            $request->input('product.attachments')
        );

        Cache::delete(Product::CACHE_NAME);
        Cache::delete("product:{$this->product->id}");

        Toast::info('Товар успешно обновлен');
        return redirect()->route('platform.products.list')->with('success');
    }

    public function create(Request $request): RedirectResponse
    {
        $product = new Product();
        $product->fill($this->getData($request));
        $product->save();

        $product->categories()->sync($request->input('product.categories'));

        $product->attachments('product')->syncWithoutDetaching(
            $request->input('product.attachments')
        );

        Cache::delete(Product::CACHE_NAME);

        Toast::info('Товар успешно добавлен');
        return redirect()->route('platform.products.list')->with('success');
    }

    private function getData(Request $request): array
    {
        $data = $request->input('product');

        if (!empty($data['active'])) {
            $data['active'] = true;
        } else {
            $data['active'] = false;
        }

        return $data;
    }

}
