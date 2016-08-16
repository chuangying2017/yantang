<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TickerApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_show_all_tickets()
    {
        $this->json('get', 'promotions/tickets', [], $this->getAuthHeader());

        $this->assertResponseOk();
    }

    /** @test */
    public function user_can_get_a_coupon_ticket()
    {
        $this->json('post', 'promotions/tickets',
            [
                'coupon_id' => 13
            ],
            $this->getAuthHeader());

        $this->assertResponseStatus(201);

        $this->seeInDatabase('user_promotion', ['user_id' => 1, 'promotion_id' => 13]);


        $this->json('post', 'promotions/tickets',
            [
                'coupon_id' => 13
            ],
            $this->getAuthHeader());

        $this->assertResponseStatus(400);

    }
}
