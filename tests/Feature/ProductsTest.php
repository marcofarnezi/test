<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductsTest extends TestCase
{
    public function testShouldReturn200Code()
    {
        $response = $this->getJson('api/products');
        $response->assertStatus(200);
    }
}
