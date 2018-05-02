<?php namespace App\Services\Order\Generator;

use App\Services\Promotion\GiftcardService;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class CheckGiftcard extends GenerateHandlerAbstract {

    /**
     * @var GiftcardService
     */
    private $giftcardService;

    /**
     * @var PromotionAbleUserContract
     */
    private $userContract;


    /**
     * CheckCoupon constructor.
     * @param GiftcardService $giftcardService
     * @param PromotionAbleUserContract $userContract
     */
    public function __construct(GiftcardService $giftcardService, PromotionAbleUserContract $userContract)
    {
        $this->giftcardService = $giftcardService;
        $this->userContract = $userContract;
    }

    public function handle(TempOrder $temp_order)
    {
        $this->userContract->setUser($temp_order->getUser());

        $this->giftcardService
            ->setUser($this->userContract)
            ->setItems($temp_order)
            ->checkRelated()
            ->checkUsable();

        $temp_order->setGiftcards($this->giftcardService->getRules());

        return $this->next($temp_order);
    }


}
