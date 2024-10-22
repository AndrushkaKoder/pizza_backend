<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class GetProductTest extends TestCase
{

    private Product|Model $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::query()->create([
            'title' => 'test_case',
            'price' => 500,
            'weight' => 0,
            'active' => true
        ]);
    }

    public function test_that_one_product_return(): void
    {
        $response = $this->get(route('products.show', ['id' => $this->product->id]));
        $response->assertOk();
        $response->assertJsonStructure([
            "data" => [
                "id",
                "title",
                "description",
                "weight",
                "price",
                "discount",
                "categories",
                "images"
            ]
        ]);
    }

}
