<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartApiTest extends TestCase {

    /** @test */
    public function it_can_get_all_carts()
    {
        $this->get('/mall/cart');


        $this->assertResponseStatus(302);

        $user = \App\Models\Access\User\User::create();

        $this->actingAs($user);

        $this->get('/mall/cart');

        $this->dumpResponse();

        $this->assertResponseStatus(200);

    }

    /** @test */
    public function it_can_add_a_sku_to_cart()
    {

    }
}
