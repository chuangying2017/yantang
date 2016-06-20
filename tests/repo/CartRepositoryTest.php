<?php

use App\Repositories\Cart\CartRepositoryContract;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartRepositoryTest extends TestCase {
    use DatabaseTransactions;



    public function setUp()
    {
        parent::setUp();

    }

    /** @test */
    public function it_can_add_a_sku_to_carts()
    {

    }



}
