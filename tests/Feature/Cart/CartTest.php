<?php

namespace Tests\Feature\Cart;

use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{

    private User|Authenticatable $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory(1)->create()->first();
        Sanctum::actingAs($this->user);
        $this->product = Product::factory(1)->create()->first();
    }

    public function test_get_cart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertOk();
    }

    public function test_that_cart_created(): void
    {
        $response = $this->post(route('cart.store', [
            'product' => $this->product->id
        ]));

        $response->assertOk();
    }

}
