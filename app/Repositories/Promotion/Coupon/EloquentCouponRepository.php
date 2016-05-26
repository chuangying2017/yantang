<?php namespace App\Repositories\Promotion\Coupon;

use App\Models\Promotion\Coupon;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Counter;

class EloquentCouponRepository extends PromotionRepositoryAbstract {

    use Counter;

    protected function init()
    {
        $this->setModel(Coupon::class);
    }


    protected function attachRelation($coupon_id, $data)
    {
        $this->createCounter($coupon_id, $data['total'], $data['effect_days']);
    }

    protected function updateRelation($coupon_id, $data)
    {
        $this->updateCounter($coupon_id, $data['total'], $data['effect_days']);
    }
}
