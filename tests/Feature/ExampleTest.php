<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_index_page_return_not_found(): void
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }
}
