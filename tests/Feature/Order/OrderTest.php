<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Status;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{

    use RefreshDatabase;

    private Generator $faker;
    private User|Authenticatable $user;
    private Cart $cart;
    private Product $product;
    private Order $order;
    private Status $status;
    private Payment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
        $this->user = User::factory()->create()->first();
        Sanctum::actingAs($this->user);

        $this->product = Product::factory()->create()->first();

        $this->status = Status::factory()->create(['id' => 1]);
        $this->payment = Payment::factory()->create();
        $this->order = Order::factory()->create([
            'status_id' => $this->status->id,
            'payment_id' => $this->payment->id,
            'user_id' => $this->user->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function test_that_cart_created(): void
    {
        $response = $this->post(route('cart.create', [
            'product' => $this->product->id
        ]));
        $response->assertOk();
    }

    /**
     * @test
     * @return void
     */
    public function test_that_order_created(): void
    {
        $cart = Cart::query()->create([
            'user_id' => $this->user->id,
            'total_sum' => $this->product->getPrice()
        ]);

        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->getPrice()
        ]);

        $response = $this->post(route('order.create', [
            "phone" => "+7" . $this->faker->phoneNumber,
            "address" => $this->faker->address,
            "payment_id" => $this->payment->id,
            "delivery_time" => '2024-01-01'
        ]));
        $response->assertStatus(201);
    }

    /**
     * @test
     * @return void
     */
    public function test_that_order_without_data_cant_created(): void
    {
        $cart = Cart::query()->create([
            'user_id' => $this->user->id,
            'total_sum' => $this->product->getPrice()
        ]);

        $cart->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->getPrice()
        ]);

        $response = $this->postJson(route('order.create'));
        $response->assertStatus(422);
    }

    /**
     * @test
     * @return void
     */
    public function test_that_order_status_changed(): void
    {
        $response = $this->putJson(route('order.change_status', [
            'order' => $this->order->id,
            'statusId' => $this->status->id,
        ]));

        $response->assertOk();
    }

    /**
     * @test
     * @return void
     */
    public function test_that_payment_type_changed(): void
    {
        $response = $this->put(route('order.change_payment', [
            'order' => $this->order->id,
            'paymentId' => $this->payment->id
        ]));

        $response->assertOk();
    }

}
