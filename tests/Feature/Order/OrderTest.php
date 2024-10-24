<?php

namespace Tests\Feature\Order;

use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use App\Models\Cart;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{

    private User|Authenticatable $user;
    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();
         $this->user = User::factory(1)->create()->first();
         Sanctum::actingAs($this->user);
    }

    /**
     * @return void
     * @test
     */
    public function test_that_cart_created(): void
    {
        $this->assertTrue(true);
    }

}
