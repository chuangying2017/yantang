<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MallProductApiTest extends TestCase
{
    /** @test */
    public function it_can_get_products_lists()
    {
        $this->json('GET', 'mall/products');

        $this->dumpResponse();
    }

    /** @test */
    public function it_can_get_a_product_detail()
    {
        $this->json('GET', 'mall/products/1');

        $this->dumpResponse();

//        $this->assertResponseStatus(404);
    }
}
