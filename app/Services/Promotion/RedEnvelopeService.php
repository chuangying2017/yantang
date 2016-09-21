<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\Coupon\CouponRepositoryContract;
use App\Repositories\RedEnvelope\RedEnvelopeReceiveRepository;
use App\Repositories\RedEnvelope\RedEnvelopeRecordRepository;
use App\Services\Promotion\Support\PromotionAbleUserContract;
use Carbon\Carbon;

class RedEnvelopeService implements PromotionDispatcher {

    /**
     * RedEnvelopeService constructor.
     * @param RedEnvelopeRecordRepository $recordRepo
     * @param RedEnvelopeReceiveRepository $receiveRepo
     * @param CouponService $couponService
     * @param CouponRepositoryContract $couponRepo
     */
    public function __construct(
        RedEnvelopeRecordRepository $recordRepo,
        RedEnvelopeReceiveRepository $receiveRepo,
        CouponService $couponService,
        CouponRepositoryContract $couponRepo
    )
    {
        $this->recordRepo = $recordRepo;
        $this->receiveRepo = $receiveRepo;
        $this->couponService = $couponService;
        $this->couponRepo = $couponRepo;
    }

    public function dispatch(PromotionAbleUserContract $user, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_USER, $source_id = 0)
    {
        //检查红包有效期、剩余数量
        $record = $this->recordRepo->get($promotion_id);

        if ($record['start_time'] > Carbon::now() || $record['end_time'] < Carbon::now()) {
            return false;
        }

        if ($record['total'] > 0 && $record['total'] <= $record['dispatch']) {
            return false;
        }

        $record_id = $record['id'];

        //检查用户是否领过
        $receive = $this->receiveRepo->getByUser($record_id, $user->getUserId());
        if ($receive) {
            return $receive;
        }

        //随机获取红包优惠券内容
        $record->load('rule');
        if (!$record['rule'] && !$record['rule']['coupons']) {
            return false;
        }

        $coupon = $this->getCoupon($record['rule']['coupons']);
        if (!$coupon) {
            return false;
        }
        //派发优惠券
        $ticket = $this->couponService->dispatchWithoutCheck($user->getUserId(), $coupon, PromotionProtocol::TICKET_RESOURCE_OF_RED_ENVELOPE, $record_id);

        return $this->receiveRepo->createReceiver($record_id, $user->getUserId(), $ticket['id'], $ticket['promotion_id'], $coupon['content']);
    }

    protected function getCoupon($rule_coupons)
    {
        for ($i = 0; $i < count($rule_coupons); $i++) {
            $coupon_id = array_get($rule_coupons, array_rand($rule_coupons));

            $coupon = $this->couponRepo->getCouponsById($coupon_id, false);

            if ($coupon) {
                return $coupon;
            }
        }

        return false;
    }

    public function dispatchWithoutCheck($user_id, $promotion_id, $source_type = PromotionProtocol::TICKET_RESOURCE_OF_RED_ENVELOPE, $source_id = 0)
    {
        // TODO: Implement dispatchWithoutCheck() method.
    }

    /**
     * @var RedEnvelopeRecordRepository
     */
    private $recordRepo;
    /**
     * @var RedEnvelopeReceiveRepository
     */
    private $receiveRepo;
    /**
     * @var CouponService
     */
    private $couponService;
    /**
     * @var CouponRepositoryContract
     */
    private $couponRepo;
}
