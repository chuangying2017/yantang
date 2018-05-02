<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\GiftcardService;
use App\Services\Promotion\CouponService;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class UseGiftcard extends GenerateHandlerAbstract {

    /**
     * @var GiftcardService
     */
    private $giftcardService;
    private $couponService;

    /**
     * @var PromotionAbleUserContract
     */
    private $userContract;

    /**
     * UseGiftcard constructor.
     * @param GiftcardService $giftcardService
     * @param PromotionAbleUserContract $userContract
     */
    public function __construct(GiftcardService $giftcardService, CouponService $couponService, PromotionAbleUserContract $userContract)
    {
        $this->giftcardService = $giftcardService;
        $this->couponService = $couponService;
        $this->userContract = $userContract;
    }

    public function handle(TempOrder $temp_order)
    {
        $ticket = $temp_order->getRequestGiftcard();

        $this->userContract->setUser($temp_order->getUser());
        $temp_order->setRules($temp_order->getGiftcards());
        $giftcards = $temp_order->getGiftcards();

        $rule_key = $this->findRuleKey($ticket, $giftcards);

        if (!is_null($rule_key)) {
            $this->giftcardService
                ->setUser($this->userContract)
                ->setItems($temp_order);

            if (array_get($giftcards, $rule_key . '.using', 0) == 1) {
                $success = $this->giftcardService->setNotUsing($rule_key);
            } else {
                $success = $this->giftcardService->setUsing($rule_key);
            }
            $temp_order->setGiftcards($this->giftcardService->getRules());
        }

        $this->couponService
            ->setUser($this->userContract)
            ->setItems($temp_order)
            ->checkUsable();

        return $this->next($temp_order);
    }

    protected function findRuleKey($ticket_id, $giftcards)
    {
        foreach ($giftcards as $key => $coupon) {
            if (array_get($coupon, 'ticket.id') == $ticket_id) {
                return $key;
            }
        }
        return null;
    }


}
