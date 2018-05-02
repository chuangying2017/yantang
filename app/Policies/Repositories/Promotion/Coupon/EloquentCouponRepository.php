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
        if (isset($data['total']) && isset($data['effect_days'])) {
            $this->updateCounter($coupon_id, $data['total'], $data['effect_days']);
        }
    }

    public function get($promotion_id, $with_detail = true)
    {
        return $promotion_id instanceof Coupon ? $promotion_id->load('counter') : Coupon::with('counter')->findOrFail($promotion_id);
    }

    public function getCouponsById($coupon_ids, $with_detail = true)
    {
		
        if ($with_detail) {
			//file_put_contents("coupon_ids.txt",date("Y-m-d H:i:s")."\nlog=".json_encode(Coupon::with('rules', 'counter')->find($coupon_ids))."\n\n");	
            return Coupon::with('rules', 'counter')->find($coupon_ids);
        }
		
        return Coupon::query()->find($coupon_ids);
    }

    public function getUsefulPromotions()
    {
        $tickets = $this->ticketRepo->getCouponTicketsOfUser(access()->id(), PromotionProtocol::STATUS_OF_TICKET_OK, false);

        $origin_coupons = $this->getCouponsById($tickets->pluck('promotion_id')->all());

        $coupons = [];

        foreach ($tickets as $ticket_key => $ticket) {
            foreach ($origin_coupons as $coupon_key => $coupon) {
                if ($ticket['promotion_id'] == $coupon['id']) {
                    $coupons[$ticket['id']] = clone $coupon;
                    $coupons[$ticket['id']]['ticket'] = $ticket;
                    break;
                }
            }
        }
        return $coupons;
    }

}
