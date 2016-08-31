<?php

use App\Models\Promotion\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Coupon::truncate();

        $data = [
            'name' => '满30瓶减20元',
            'desc' => '订购牛奶,满30瓶减20元',
            'content' => '20元',
            'cover_image' => 'image_url',
            'start_time' => '2016-08-27 00:00:00',
            'end_time' => '2016-11-01 00:00:00',
            'active' => 1,
            'effect_days' => '30', //领券后有效时间
            'total' => 1000000,
            'rules' => [
                [
                    'name' => '满30瓶减20元',
                    'desc' => '订购牛奶,满30瓶减20元',
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
                        'min' => 30,
                        'max' => null
                    ],
                    'discount' => [
                        'type' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT,
                        'mode' => \App\Services\Promotion\PromotionProtocol::DISCOUNT_MODE_OF_DECREASE,
                        'value' => 20
                    ],
                    'weight' => 100,
                    'multi' => 1,
                    'memo' => '备注'
                ]
            ],
        ];

        app()->make(\App\Repositories\Promotion\Coupon\EloquentCouponRepository::class)->create($data);

    }
}
