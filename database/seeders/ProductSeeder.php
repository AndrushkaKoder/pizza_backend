<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Orchid\Attachment\File;
use Illuminate\Support\Facades\File as FileFacade;

class ProductSeeder extends Seeder
{

    private readonly string $pathToTempFiles;

    public function __construct()
    {
        $this->pathToTempFiles = storage_path('temp');
    }

    public function run(): void
    {
        $data = include_once storage_path('seed/products/products.php');

        if (Product::query()->count()) return;

        foreach ($data as $productItem) {
            $product = new Product();
            $product->fill([
                'title' => env('APP_ENV') === 'local' ? $productItem['title'] : 'TEST_' . $productItem['title'],
                'description' => $productItem['description'],
                'weight' => $productItem['weight'],
                'price' => $productItem['price'],
            ])->save();

            $category = Category::query()->whereTitle($productItem['category'])->first();
            if ($category) {
                $category->products()->attach($product);
            }

            if ($photo = $productItem['img']) {
                $this->attachFromUrl($product, $photo);
            }
        }

        $this->deleteTempFiles();
    }

    private function attachFromUrl(Product $product, string $path): void
    {
        try {
            if (!is_dir($this->pathToTempFiles)) mkdir($this->pathToTempFiles);
            file_put_contents($this->pathToTempFiles . '/' . basename($path), file_get_contents($path));
            $file = new UploadedFile($this->pathToTempFiles . '/' . basename($path), basename($path));

            $orchidFile = new File($file, 'public', 'product');
            $attachment = $orchidFile->load();
            $product->attachments()->syncWithoutDetaching($attachment);
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
            return;
        }
    }

    private function deleteTempFiles(): void
    {
        $tempFiles = FileFacade::allFiles($this->pathToTempFiles);
        foreach ($tempFiles as $file) {
            FileFacade::delete($file);
        }
    }
}
