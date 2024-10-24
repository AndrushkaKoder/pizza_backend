<?php

namespace Tests\Feature\User;

use Illuminate\Support\Str;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    private array $userData = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->userData = [
            "name" => "Test_" . Str::random(20),
            "email" => Str::random(30) . '@gmail.com',
            "phone" => "79" . str_repeat(rand(1000, 10000), 2),
            "password" => 12345,
            "password_confirmation" => 12345
        ];
    }

    public function test_that_user_was_register(): void
    {
        $response = $this->post(route('register'), $this->userData);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "success",
            "message"
        ]);
    }



}
