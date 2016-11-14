<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminPromotionApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_all_coupons()
    {
        $this->json('get', 'admin/promotions/coupons', ['all' => 0], $this->getAuthHeader());

        $this->dump();

        $this->assertResponseOk();
    }

    /** @test */
    public function it_can_create_a_coupon()
    {
        $data = $this->getCouponDataOfShouDan();

        $this->json('post',
            'admin/promotions/coupons',
            $data,
            $this->getAuthHeader()
        );

        $promotion = $this->getResponseData('data');

        $this->seeInDatabase('promotion_counter', ['promotion_id' => $promotion['id']]);

        $this->echoJson();

        $this->assertResponseStatus(201);

        return $promotion['id'];
    }


    protected function getCouponData()
    {
        $data = [
            'name' => '满赠优惠券',
            'desc' => '订购牛奶, 15送3',
            'content' => '3元',
            'cover_image' => 'image_url',
            'start_time' => '2016-08-09 10:00:00',
            'end_time' => '2016-08-29 10:00:00',
            'active' => 1,
            'effect_days' => '10', //领券后有效时间
            'total' => 10000,
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
                        'type' => \App\Services\Promotion\PromotionProtocol::ITEM_TYPE_OF_GROUP,
                        'values' => 1,
                    ],
                    'range' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::RANGE_TYPE_OF_QUANTITY,
                        'min' => 15,
                        'max' => 15
                    ],
                    'discount' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_TYPE_OF_PRODUCT,
                        'mode' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_MODE_OF_EQUAL,
                        'value' => 3
                    ],
                    'weight' => 100,
                    'multi' => 1,
                    'memo' => '备注'
                ]
            ],
        ];

        return $data;
    }


    protected function getCouponDataOfManjian()
    {
        $data = [
            'name' => '满减优惠券',
            'desc' => '订购牛奶, 满20瓶减20元',
            'content' => '20元',
            'cover_image' => 'image_url',
            'start_time' => '2016-08-09 10:00:00',
            'end_time' => '2016-08-29 10:00:00',
            'active' => 1,
            'rules' => [
                [
                    'name' => '满20瓶减20元 优惠券',
                    'desc' => '订购牛奶, 满20瓶减20元',
                    'qualify' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::QUALI_TYPE_OF_ALL,
                        'quantity' => 1,
                        'values' => null
                    ],
                    'items' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::ITEM_TYPE_OF_GROUP,
                        'values' => 1,
                    ],
                    'range' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::RANGE_TYPE_OF_QUANTITY,
                        'min' => 20,
                        'max' => null
                    ],
                    'discount' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT,
                        'mode' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_MODE_OF_DECREASE,
                        'value' => 2000
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


    protected function getCouponDataOfShouDan()
    {
        $data = [
            'name' => '满20瓶减20元 满减优惠券',
            'desc' => '首单下单后可领,订购牛奶,满20瓶减20元',
            'content' => '20元',
            'cover_image' => 'image_url',
            'start_time' => '2016-08-09 10:00:00',
            'end_time' => '2016-09-29 10:00:00',
            'active' => 0,
            'rules' => [
                [
                    'name' => '满20瓶减20元 优惠券',
                    'desc' => '首单下单后可领,订购牛奶,满20瓶减20元',
                    'qualify' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::QUALI_TYPE_OF_FIRST_PRE_ORDER,
                        'quantity' => 1,
                        'values' => null
                    ],
                    'items' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::ITEM_TYPE_OF_GROUP,
                        'values' => 1,
                    ],
                    'range' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::RANGE_TYPE_OF_QUANTITY,
                        'min' => 20,
                        'max' => null
                    ],
                    'discount' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT,
                        'mode' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_MODE_OF_DECREASE,
                        'value' => 2000
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


    /** @test */
    public function it_can_dispatch_tickets_to_users()
    {
        $coupon_id = 8;
        $user_ids = 100;
        $this->json('post', 'admin/promotions/tickets', [
            'coupon_id' => $coupon_id,
            'user_ids' => $user_ids,
            'quantity' => 4,
        ], $this->getAuthHeader());

        $this->assertResponseOk();

        $this->json('get', 'promotions/tickets', [], $this->getAuthHeader($user_ids));

        $this->dump();

    }

    /** @test */
    public function it_can_update_a_coupon()
    {
        $promotion_id = $this->it_can_create_a_coupon();

        $data = [
            'id' => $promotion_id,
            'name' => 'change14',
            'desc' => 'change14',
            'content' => 'change12',
            'active' => 1,
        ];

        $counter = [
            'promotion_id' => $promotion_id,
            'total' => 100012,
            'effect_days' => 32
        ];

        $this->json('put',
            'admin/promotions/coupons/' . $promotion_id,
            array_merge($data, $counter),
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(200);

        $this->seeInDatabase('promotions', $data);
        $this->seeInDatabase('promotion_counter', $counter);

    }
}
