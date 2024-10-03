<?php

namespace App\Console\Commands;

use App\Models\ProductType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ParseMenu extends Command
{
    protected $signature = 'app:parse {--pizza}';
    protected $description = 'Parse menu from Peppers Pizza =)';

    private string $pizzaUrl = 'https://pepperspizza.ru/api/json/menu/list?restaurant=f64da9e1-8113-4354-aaf3-a4d676f926b3&groupId=efdd71a8-335f-4546-a672-ad88006d473c';
    private string $drinksUrl = 'https://pepperspizza.ru/api/json/menu/list?restaurant=f64da9e1-8113-4354-aaf3-a4d676f926b3&groupId=1be9b67c-d51d-439d-acef-aa7900c70729';

    public function handle(): void
    {
        $data = array_merge(
            $this->getPizza(),
            $this->getDrinks()
        );
        try {
            if (!$data) throw new \Exception('OOOPS, error');

            $this->info('success parsing');
            $this->saveProductsSeeder($data);

        } catch (\Throwable $throwable) {
            $this->error('some errors, check ' . self::class);
            echo $throwable->getMessage();
        }
    }

    private function getPizza(): ?array
    {
        $data = Http::get($this->pizzaUrl);

        if (!$data) return null;

        return array_map(function ($item) {
            return [
                'title' => $item['Product']['Name'] ?? '',
                'description' => $item['Product']['Options']['тонкое'][0]['ProductDescription'] ?? $item['Product']['Options']['стандартное'][0]['ProductDescription'] ?? '',
                'weight' => $item['Product']['Options']['тонкое'][0]['Weight'] ?? $item['Product']['Options']['стандартное'][0]['Weight'] ?? 0,
                'price' => $item['Product']['Options']['тонкое'][0]['Price'] ?? $item['Product']['Options']['стандартное'][0]['Price'] ?? 0,
                'img' => $item['Product']['NormalImage']['Path'] ? 'https://pepperspizza.ru' . $item['Product']['NormalImage']['Path'] : '',
                'type_id' => ProductType::T_PIZZA
            ];
        }, $data['answer']['Items']);

    }

    private function getDrinks(): ?array
    {
        $data = Http::get($this->drinksUrl);

        return array_map(function ($item) {
            return [
                'title' => $item['Product']['Name'] ?? '',
                'description' => '',
                'weight' => 0,
                'price' => $item['Product']['Price'] ?? 0,
                'img' => $item['Product']['NormalImage']['Path'] ? 'https://pepperspizza.ru' . $item['Product']['NormalImage']['Path'] : '',
                'type_id' => ProductType::T_DRINK
            ];
        }, $data['answer']['Items']);

    }

    private function saveProductsSeeder(array $data): void
    {
        $dir = storage_path('seed/products');
        if (!is_dir($dir)) mkdir($dir, '775', true);

        file_put_contents($dir . '/products.php', '<?php return ' . var_export($data, true) . ';', FILE_APPEND);
    }
}
