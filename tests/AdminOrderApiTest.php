<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminOrderApiTest extends TestCase
{
    /** @test */
    public function it_can_get_paginate_mall_orders()
    {
        $user_id = 1;

        $this->json('get', 'admin/mall/orders',
            ['keyword' => '131'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_paginate_special_orders()
    {
        $user_id = 1;

        $this->json('get', 'admin/special/orders',
            ['keyword' => '104160622827037798579'],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        $this->dumpResponse();

        $this->assertResponseOk();

    }
}
