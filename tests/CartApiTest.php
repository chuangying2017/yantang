<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartApiTest extends TestCase {

//    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUser();
    }

    /** @test */
    public function it_can_get_all_carts()
    {

        $this->get('/mall/cart');

        $this->dumpResponse();

        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_can_add_a_sku_to_cart()
    {
        $this->json('POST', 'mall/cart', ['product_sku_id' => 2, 'quantity' => 100]);
        $this->dumpResponse();
        $this->assertResponseStatus(201);
    }


}
