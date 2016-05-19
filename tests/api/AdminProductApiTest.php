<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminProductApiTest extends TestCase {

    /** @test */
    public function it_return_a_products_list()
    {
        $query = [
            'brand' => 7,
        ];
        $this->json('get', '/admin/products', $query);

        $this->dumpResponse();
    }

    /** @test */
    public function it_return_a_product()
    {
        $product_id = 1;
        $this->json('get', '/admin/products/' . $product_id);

        $this->dumpResponse();
    }

    /** @test */
    public function it_return_subscribe_products()
    {
        $this->json('get', '/stations/products');

        $this->dumpResponse();
    }

}
