<?php namespace App\Repositories\Promotion\Coupon;

use App\Models\Promotion\Coupon;
use App\Models\Promotion\Ticket;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Counter;
use App\Services\Promotion\PromotionProtocol;

class EloquentCouponRepository extends PromotionRepositoryAbstract implements CouponRepositoryContract {

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

    public function get($promotion_id, $with_detail = true)
    {
        return $promotion_id instanceof Coupon ? $promotion_id->load('counter') : Coupon::with('counter')->find($promotion_id);
    }

    public function getCouponsById($coupon_ids)
    {
        return Coupon::with('rules', 'counter')->find($coupon_ids);
    }

    public function getUsefulPromotions()
    {
        $ticket_coupon_ids = Ticket::query()->where('status', PromotionProtocol::STATUS_OF_TICKET_OK)->where('user_id', access()->id())->effect()->pluck('promotion_id')->all();

        return $this->getCouponsById($ticket_coupon_ids);
    }
}
