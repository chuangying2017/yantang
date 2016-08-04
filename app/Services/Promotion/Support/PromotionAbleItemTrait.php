<?php namespace App\Services\Promotion\Support;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Support\Benefit\PromotionAbleItemBenefitContract;
use App\Services\Promotion\Support\Benefit\PromotionCredit;
use App\Services\Promotion\Support\Benefit\PromotionExpressFee;
use App\Services\Promotion\Support\Benefit\PromotionGift;

trait PromotionAbleItemTrait {

    /**
     * @var PromotionAbleItemBenefitContract
     */
    protected $promotion_benefit_handler;

    protected $promotion_credits = null;
    protected $promotion_gifts = null;
    protected $promotion_express_fee = 0;

    protected function getBenefitHandlerByType($type)
    {
        $data = [
            PromotionProtocol::DISCOUNT_TYPE_OF_CREDITS => [
                'handler' => PromotionCredit::class,
                'var' => &$this->promotion_credits
            ],
            PromotionProtocol::DISCOUNT_TYPE_OF_GIFT => [
                'handler' => PromotionGift::class,
                'var' => &$this->promotion_gifts
            ],
            PromotionProtocol::DISCOUNT_TYPE_OF_EXPRESS => [
                'handler' => PromotionExpressFee::class,
                'var' => &$this->promotion_express_fee
            ],
        ];

        $handler = array_get($data, $type . '.handler', null);

        if (is_null($handler)) {
            throw new \Exception('优惠项目不存在');
        }

        return app()->make($handler)->init(array_get($data, $type . '.var'));
    }

    public function setPromotionBenefit($benefit_type)
    {
        $this->promotion_benefit_handler = $this->getBenefitHandlerByType($benefit_type);
    }

    public function addPromotionBenefit($benefit, $key = null)
    {
        $this->promotion_benefit_handler->add($benefit, $key);
    }

    public function removePromotionBenefit($benefit, $key = null)
    {
        $this->promotion_benefit_handler->remove($benefit, $key);
    }

    public function getPromotionBenefit($key = null)
    {
        $this->promotion_benefit_handler->get($key);
    }

}
