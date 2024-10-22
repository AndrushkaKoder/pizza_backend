<?php

namespace Tests\Feature\Product;

use Tests\TestCase;

class GetProductsListTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_example(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertOk();
    }
}
