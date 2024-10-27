<?php

namespace Tests\Feature\Product;

use Tests\TestCase;

class GetProductsListTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_products_list_get(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => []
        ]);
    }
}
