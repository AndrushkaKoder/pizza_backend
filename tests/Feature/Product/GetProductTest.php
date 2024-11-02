<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class GetProductTest extends TestCase
{

    /**
     * @return void
     * @test
     */
    public function test_that_one_product_return(): void
    {
        $product = Product::factory()->create();
        $response = $this->get(route('products.show', ['id' => $product->id]));
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

    /**
     * @test
     * @return void
     */
    public function test_that_product_maybe_not_found(): void
    {
        $response = $this->get(route('products.show', ['id' => 100500]));
        $response->assertStatus(404);
    }

}
