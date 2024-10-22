<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\File;

class ProductSeeder extends Seeder
{

    public function run(): void
    {
        if (!Product::query()->count()) {
            $data = include_once storage_path('seed/products/products.php');
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
        }

        Storage::deleteDirectory(storage_path('temp'));
    }

    private function attachFromUrl(Product $product, string $path): void
    {
        $pathToSave = storage_path('temp');
        if (!is_dir($pathToSave)) mkdir($pathToSave, 777);
        file_put_contents($pathToSave . '/' . basename($path), file_get_contents($path));
        $file = new UploadedFile(storage_path('temp/' . basename($path)), basename($path));

        $orchidFile = new File($file, 'public', 'product');
        $attachment = $orchidFile->load();
        $product->attachments()->syncWithoutDetaching($attachment);
        Storage::delete($attachment->physicalPath());
    }
}
