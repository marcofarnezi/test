<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Infrastructure\Framework\Models\Product;

class ProductTest extends TestCase
{
    public function testShouldReturn200Code()
    {
        $product = new Product();
        $product->title = 'test';
        $product->price = 100;
        $product->save();
        $response = $this->getJson('api/product/'.$product->id);
        $response->assertStatus(200);
        $this->assertStringMatchesFormat(
            '{"id":'.$product->id.',"title":"test","price":100,"description":null}',
            $response->getContent()
        );
        $product->delete();
    }
}
