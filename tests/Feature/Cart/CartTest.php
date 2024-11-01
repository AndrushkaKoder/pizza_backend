<?php

namespace Tests\Feature\Cart;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{

    private User|Authenticatable $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->first();
        Sanctum::actingAs($this->user);
    }

    /**
     * @test
     * @return void
     */
    public function test_get_cart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertOk();
    }

    /**
     * @test
     * @return void
     */
    public function test_that_cart_created(): void
    {
        $response = $this->post(route('cart.create', [
            'product' => Product::factory()->create()->id
        ]));

        $response->assertOk();
    }

    /**
     * @test
     * @return void
     */
    public function test_that_deactivate_product_dont_add_to_cart(): void
    {
        $response = $this->post(route('cart.create', [
            'product' =>  Product::factory()->create(['active' => false])->id
        ]));

        $response->assertStatus(400);
    }

    /**
     * @test
     * @return void
     */
    public function test_that_cant_add_more_products_than_specified_in_category(): void
    {

        $product = Product::factory()->create();
        $category = Category::factory()->create(['max_for_order' => 10]);
        $category->products()->sync($product->id);

        $cart = Cart::query()->create([
            'user_id' => $this->user->id,
            'total_sum' => $product->getPrice()
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 11,
            'price' => $product->getPrice()
        ]);

        $response = $this->post(route('cart.create', [
            'product' =>  $product->id
        ]));


        $response->assertStatus(400);
    }

}
