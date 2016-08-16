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
        $counter = $this->createCounter($coupon_id, $data['total'], $data['effect_days']);
    }

    protected function updateRelation($coupon_id, $data)
    {
        $this->updateCounter($coupon_id, $data['total'], $data['effect_days']);
    }

    public function get($promotion_id, $with_detail = true)
    {
        return $promotion_id instanceof Coupon ? $promotion_id->load('counter') : Coupon::with('counter')->findOrFail($promotion_id);
    }

    public function getCouponsById($coupon_ids)
    {
        return Coupon::with('rules', 'counter')->find($coupon_ids);
    }

    public function getUsefulPromotions()
    {
        $tickets = Ticket::query()->where('status', PromotionProtocol::STATUS_OF_TICKET_OK)->where('user_id', access()->id())->effect()->get();

        $coupons = $this->getCouponsById($tickets->pluck('promotion_id')->all());

        foreach ($coupons as $coupon_key => $coupon) {
            foreach ($tickets as $ticket) {
                if ($ticket['promotion_id'] == $coupon['id']) {
                    $coupons[$coupon_key]->ticket = $ticket;
                    break;
                }
            }
        }
        return $coupons;
    }
}
