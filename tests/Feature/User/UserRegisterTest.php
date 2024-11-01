<?php

namespace Tests\Feature\User;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{

    use RefreshDatabase;

    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    /**
     * @test
     * @return void
     */
    public function test_that_user_was_registered(): void
    {
        $response = $this->postJson(route('register'), [
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            "password" => 12345,
            "password_confirmation" => 12345
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "success",
            "message",
            "token"
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function test_that_register_password_must_be_confirmed(): void
    {
        $response = $this->postJson(route('register'), [
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            "password" => 12345,
            "password_confirmation" => null
        ]);

        $response->assertStatus(422);
    }

}
