<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MallOrderApiTest extends TestCase
{


    /** @test */
    public function it_can_get_orders_lists()
    {
        $this->setUser();

        $this->json('get', 'mall/orders');
        $this->dumpResponse();
        $this->assertResponseOk();

    }
}
