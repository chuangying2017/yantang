<?php namespace App\Services\Promotion\Rule\Benefit\Setter;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Data\SpecifyRuleDataContract;

trait PromotionAbleItemTrait {

    /**
     * @var PromotionAbleItemBenefitContract
     */
    protected $promotion_benefit_handler;

    protected $promotion_express_fee = 0;

    //优惠规则
    protected $promotions;

    protected $rules;

    protected $coupons;

    protected $campaigns;

    protected function getBenefitHandlerByType($type)
    {
        $data = [
            PromotionProtocol::DISCOUNT_TYPE_OF_AMOUNT => [
                'handler' => PromotionAmount::class,
                'var' => &$this->products_amount
            ],
            PromotionProtocol::DISCOUNT_TYPE_OF_PRODUCT => [
                'handler' => PromotionProducts::class,
                'var' => &$this->skus
            ],
            PromotionProtocol::DISCOUNT_TYPE_OF_EXPRESS => [
                'handler' => PromotionExpressFee::class,
                'var' => &$this->promotion_express_fee
            ],
            PromotionProtocol::DISCOUNT_TYPE_OF_SPECIAL_PRICE => [
                'handler' => PromotionSpecialPrice::class,
                'var' => &$this->skus
            ],
        ];

        $handler = array_get($data, $type . '.handler', null);
        if (is_null($handler)) {
            throw new \Exception('优惠项目不存在');
        }

        $handler_obj = app()->make($handler);
        $handler_obj->init($data[$type]['var']);
        return $handler_obj;
    }

    protected function setPromotionBenefit($benefit_type)
    {
        $this->promotion_benefit_handler = $this->getBenefitHandlerByType($benefit_type);
        return $this;
    }

    protected function addPromotionBenefit($benefit)
    {
        $this->promotion_benefit_handler->add($benefit);
    }

    protected function removePromotionBenefit($benefit)
    {
        $this->promotion_benefit_handler->remove($benefit);
    }

    public function addPromotion(SpecifyRuleDataContract $rule)
    {
        $this->promotions[$rule->getRuleID()] = $rule->getRule();

        $discount = $rule->getDiscount();

        $this->setPromotionBenefit($discount['type'])->addPromotionBenefit($rule->getBenefit());

        $rule->setUsing();

        return $this;
    }

    public function removePromotion(SpecifyRuleDataContract $rule)
    {
        $discount = $rule->getDiscount();

        $this->setPromotionBenefit($discount['type'])->removePromotionBenefit($rule->getBenefit());

        unset($this->promotions[$rule->getRuleID()]);

        $rule->unsetBenefit()->unsetUsing();

        return $this;
    }

    public function getPromotions(SpecifyRuleDataContract $rule = null)
    {
        return is_null($rule) ? $this->promotions : $this->promotions[$rule->getRuleID()];
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }


    /**
     * @return mixed
     */
    public function getCoupons($keys = null)
    {
        return $this->coupons;
    }

    public function showCoupons()
    {
        return $this->coupons;
    }

    public function showCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * @param mixed $coupons
     */
    public function setCoupons($coupons)
    {
        $this->coupons = $coupons;
    }

    /**
     * @return mixed
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * @param mixed $campaigns
     */
    public function setCampaigns($campaigns)
    {
        $this->campaigns = $campaigns;
    }

}
