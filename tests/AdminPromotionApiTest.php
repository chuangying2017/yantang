<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminPromotionApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_coupons()
    {
        $this->json('get', 'admin/promotions/coupons', [], $this->getAuthHeader());

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_coupon()
    {
        $data = $this->getCouponData();

        $this->json('post',
            'admin/promotions/coupons',
            $data,
            $this->getAuthHeader()
        );

        $promotion = $this->getResponseData('data');

        $this->seeInDatabase('promotion_counter', ['promotion_id' => $promotion['id']]);

        $this->assertResponseStatus(201);
    }


    protected function getCouponData()
    {
        $data = [
            'name' => '满赠优惠券',
            'desc' => '订购牛奶, 15送3',
            'cover_image' => 'image_url',
            'start_time' => '2016-08-09 10:00:00',
            'end_time' => '2016-08-29 10:00:00',
            'active' => 1,
            'rules' => [
                [
                    'name' => '15送3 优惠券',
                    'desc' => '订购牛奶, 15送3',
                    'qualify' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::QUALI_TYPE_OF_ALL,
                        'quantity' => 1,
                        'values' => null
                    ],
                    'items' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::ITEM_TYPE_OF_PRODUCT,
                        'values' => [3],
                    ],
                    'range' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::RANGE_TYPE_OF_QUANTITY,
                        'min' => 15,
                        'max' => 15
                    ],
                    'discount' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_TYPE_OF_PRODUCT,
                        'mode' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_MODE_OF_EQUAL,
                        'value' => [3 => 3]
                    ],
                    'weight' => 100,
                    'multi' => 1,
                    'memo' => '备注'
                ]
            ],
            'effect_days' => '10', //领券后有效时间
            'total' => 10000
        ];

        return $data;
    }
}
