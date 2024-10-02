<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ParseMenu extends Command
{
    protected $signature = 'app:parse';
    protected $description = 'Parse menu from Peppers Pizza =)';

    private string $url = 'https://pepperspizza.ru/api/json/menu/list?restaurant=f64da9e1-8113-4354-aaf3-a4d676f926b3&groupId=efdd71a8-335f-4546-a672-ad88006d473c';

    public function handle(): void
    {
        $data = $this->getData();
        try {
            if (!$data) throw new \Exception('OOOPS, error');

            $this->info('success parsing');
            $this->saveProductsSeeder($data);

        } catch (\Throwable $throwable) {
            $this->error('some errors, check ' . self::class);
            echo $throwable->getMessage();
        }
    }

    private function getData(): ?array
    {
        $data = Http::get($this->url);

        if (!$data) return null;

        return array_map(function ($item) {
            return [
                'title' => $item['Product']['Name'] ?? '',
                'description' => $item['Product']['Options']['тонкое'][0]['ProductDescription'] ?? $item['Product']['Options']['стандартное'][0]['ProductDescription'] ?? '',
                'weight' => $item['Product']['Options']['тонкое'][0]['Weight'] ?? $item['Product']['Options']['стандартное'][0]['Weight'] ?? 0,
                'price' => $item['Product']['Options']['тонкое'][0]['Price'] ?? $item['Product']['Options']['стандартное'][0]['Price'] ?? 0,
                'img' => $item['Product']['NormalImage']['Path'] ? 'https://pepperspizza.ru' . $item['Product']['NormalImage']['Path'] : '',
            ];
        }, $data['answer']['Items']);

    }

    private function saveProductsSeeder(array $data): void
    {
        $dir = storage_path('seed/products');
        if (!is_dir($dir)) mkdir($dir, '775', true);

        file_put_contents($dir . '/products.php', '<?php return ' . var_export($data, true) . ';');
    }
}
