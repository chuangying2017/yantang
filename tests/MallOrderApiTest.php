<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MallOrderApiTest extends TestCase
{


    /** @test */
    public function it_can_get_orders_lists()
    {
        $user_id = 1;

        $this->json('get', 'mall/orders',
            [
            ],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();
        $this->assertResponseOk();
    }
}
