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
        $promotion_id = 26;
        $this->json('post', 'promotions/tickets',
            [
                'coupon_id' => $promotion_id
            ],
            $this->getAuthHeader());

        $this->assertResponseStatus(201);

        $this->seeInDatabase('tickets', ['user_id' => 1, 'promotion_id' => $promotion_id]);
        $this->seeInDatabase('promotion_counter', ['promotion_id' => $promotion_id, 'dispatch' => 1]);

        $this->json('post', 'promotions/tickets',
            [
                'coupon_id' => $promotion_id
            ],
            $this->getAuthHeader());

        $this->assertResponseStatus(400);

    }
}
