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

        $user_id = 1;
        $this->json('get', 'mall/cart',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_can_add_a_sku_to_cart()
    {
        $user_id = 1;
        $this->json('POST', 'mall/cart',
            ['product_sku_id' => 2, 'quantity' => 2],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );
        $this->dumpResponse();
        $this->assertResponseStatus(201);
    }


}
