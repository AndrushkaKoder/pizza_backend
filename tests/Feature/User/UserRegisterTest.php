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

    public function test_that_user_was_registered(): void
    {
        $response = $this->post(route('register'), [
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            "password" => 12345,
            "password_confirmation" => 12345
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "success",
            "message"
        ]);
    }

}
