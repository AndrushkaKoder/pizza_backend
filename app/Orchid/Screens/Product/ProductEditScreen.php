<?php

namespace App\Orchid\Screens\Product;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\In;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
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

                    Select::make('product.categories')
                        ->title('Категория')
                        ->fromModel(Category::class, 'title')
                        ->multiple()
                        ->required(),
                ]),
                Group::make([
                    Input::make('product.price')
                        ->type('number')
                        ->title('Цена')
                        ->max(10000)
                        ->required(),

                    Input::make('product.discount_price')
                        ->title('Цена со скидкой')
                        ->type('integer')
                        ->max(10000),

                    DateTimer::make('product.discount_end')
                        ->title('Окончание скидки')
                        ->format('Y-m-d')
                        ->placeholder('Выберите день'),

                    CheckBox::make('product.discount_active')
                        ->title('Скидка активна'),

                ]),
                Group::make([
                    Input::make('product.weight')
                        ->type('integer')
                        ->max(10000)
                        ->title('Вес'),

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

        $data['active'] = boolval($request->input('product.active'));
        $data['discount_active'] = boolval($request->input('product.discount_active'));

        if ($discountDate = $data['discount_end']) {
            if (Carbon::parse($discountDate) < Carbon::now()) {
                $data['discount_end'] = null;
                $data['discount_active'] = false;
            }
        }

        return $data;
    }

}
