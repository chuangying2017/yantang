<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CouponApiTest extends TestCase {

    /** @test */
    public function it_can_get_coupons_lists()
    {
        $this->json('get', 'promotions/coupons', [], $this->getAuthHeader());

        $this->echoJson();

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
