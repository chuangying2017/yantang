<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Data\PromotionDataProtocol;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

abstract class PromotionServiceAbstract implements PromotionServiceContract {


    /**
     * @var RuleServiceContract
     */
    protected $ruleService;
    /**
     * @var PromotionSupportRepositoryContract
     */
    protected $promotionSupportRepo;

    /**
     * PromotionServiceAbstract constructor.
     * @param
     */
    public function __construct(PromotionSupportRepositoryContract $promotionSupportRepo, RuleServiceContract $ruleService)
    {
        $this->ruleService = $ruleService;
        $this->promotionSupportRepo = $promotionSupportRepo;
    }

    public function check($user, $items)
    {

        /**
         * 现有基础上增减优惠
         * 1. 检查和现有生效规则是否冲突
         * 2. 计算优惠资源
         */

    }

    public function checkRange()
    {
        foreach ($this->data->getRules() as $rule_key => $rule) {
            if ($this->ruleService->itemsInRange($this->data->getRuleUsageItems($rule_key), $this->data->getRuleRange($rule_key))) {
                $this->data->setRuleUsageInRange($rule_key);
            }
        }
    }

    public function calBenefits()
    {
        foreach ($this->data->getInRangeRules() as $rule_key => $rule) {
            $promotion_data_benefit_object = PromotionDataProtocol::getBenefitObjectHandler(
                $this->data->getRuleDiscountType($rule_key),
                $this->data,
                $rule_key
            );
            $benefits = $this->ruleService->calculatePromotionBenefits(
                $promotion_data_benefit_object->getBenefitObject(),
                $this->data->getRuleDiscount($rule_key)
            );
            $promotion_data_benefit_object->setRuleBenefit($benefits);
        }
    }

}
