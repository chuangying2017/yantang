<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartApiTest extends TestCase {

    use DatabaseTransactions;

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
        $this->seeInDatabase('carts', ['user_id' => $user_id, 'product_sku_id' => 2]);

        return $this->getResponseData('data');
    }

    /** @test */
    public function it_can_update_a_cart()
    {
       $cart =  $this->it_can_add_a_sku_to_cart();
        $user_id = 1;
        $this->json('put', 'mall/cart/' . $cart['id'],
            ['quantity' => 2],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );
        $this->dumpResponse();
        $this->assertResponseStatus(200);
        $this->seeInDatabase('carts', ['id' => $cart['id'], 'quantity' => $cart['quantity'] + 2]);
    }


}
