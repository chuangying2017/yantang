<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CouponApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_coupons_lists()
    {
        $this->json('get', 'promotions/coupons', [
            'status' => 2
        ], $this->getAuthHeader(18));

        $this->dump();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_fetch_a_coupon_ticket()
    {
        $this->json('post', 'promotions/tickets', [
            'coupon_id' => 7
        ], $this->getAuthHeader(8818));

        $this->dump();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_get_coupon_detail()
    {
        $coupon_id = 13;
        $this->json('get', 'promotions/coupons/' . $coupon_id, [], $this->getAuthHeader());

        $this->echoJson();

        $this->assertResponseOk();
    }
}
