<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{

    use RefreshDatabase;

    private User|Authenticatable $user;
    private Cart $cart;
    private Product $product;
    private Order $order;
    private Status $status;
    private Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory(1)->create()->first();
        Sanctum::actingAs($this->user);

        $this->product = Product::factory(1)->create()->first();
        $this->cart = $this->user->cart()->create([
            'total_sum' => $this->product->getPrice()
        ]);

        $this->cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->getPrice()
        ]);

        $this->order = Order::factory()->create();
        $this->status = Status::factory()->create();
        $this->payment = Payment::factory()->create();
    }

    /**
     * @return void
     * @test
     */
    public function test_that_cart_created(): void
    {
        $response = $this->post(route('cart.create', [
            'product' => $this->product
        ]));
        $response->assertOk();
    }

    public function test_that_order_created(): void
    {
        $response = $this->post(route('order.create', [
            'phone' => '79999999999',
            'address' => 'test street',
            'delivery_time' => '2024-01-01',
            'payment_id' => 1,
            'comment' => 'test comment',
        ]));

        $response->assertStatus(201);
    }

    public function test_that_order_status_changed(): void
    {
        $response = $this->get(route('order.change_status', [
            'order' => $this->order->id,
            'statusId' => $this->status->id
        ]));

        $response->assertOk();
    }

    public function test_that_payment_type_changed(): void
    {
        $response = $this->get(route('order.change_payment', [
            'order' => $this->order->id,
            'statusId' => $this->status->id
        ]));

        $response->assertOk();
    }

}
