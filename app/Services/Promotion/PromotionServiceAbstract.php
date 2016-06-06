<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Data\PromotionData;
use App\Services\Promotion\Data\PromotionDataProtocol;
use App\Services\Promotion\Rule\RuleServiceContract;

abstract class PromotionServiceAbstract {

    /**
     * @var PromotionData
     */
    private $data;
    /**
     * @var PromotionSupportRepositoryContract
     */
    private $promotionSupportRepo;
    /**
     * @var RuleServiceContract
     */
    private $ruleService;

    /**
     * PromotionServiceAbstract constructor.
     * @param
     */
    public function __construct(
        PromotionData $data,
        PromotionSupportRepositoryContract $promotionSupportRepo,
        RuleServiceContract $ruleService
    )
    {
        $this->data = $data;
        $this->promotionSupportRepo = $promotionSupportRepo;
        $this->ruleService = $ruleService;
    }

    public function check($user, $items)
    {
        $rules = $this->promotionSupportRepo->getCampaignRules();

        $this->data->initPromotionData($user, $items, $rules);

        //检查用户资格
        $this->checkQualify();

        //检查规则对象,关联规则
        $this->checkUsage();

        /**
         * 规则排序:
         * 1. 排他优先
         * 2. 分组和组内权重降序排序（分组优先,权重大优先）
         * 3. 规则对象范围小优先
         */
        $this->data->sortRules();

        /**
         * 计算生效的规则
         * 1. 计算优惠资源
         * 2. 排他生效后结束
         * 3. 组内权重高生效后跳过同组
         * 4. 记录未生效信息
         */
        $this->calBenefits();

        /**
         * 现有基础上增减优惠
         * 1. 检查和现有生效规则是否冲突
         * 2. 计算优惠资源
         */


        return $this->data->getPromotionData();
    }

    public function checkQualify()
    {
        foreach ($this->data->getRules() as $rule_key => $rule) {
            if (!$this->ruleService->canGetPromotion($this->data->getUser(), $this->data->getRuleQualify($rule_key))) {
                $this->data->removeRule($rule_key);
            }
        }
    }

    public function checkUsage()
    {
        foreach ($this->data->getRules() as $rule_key => $rule) {

            $fit_item_keys = $this->ruleService->itemsFitPromotion(
                $this->data->getItems(),
                $this->data->getRuleItems($rule_key)
            );

            if (count($fit_item_keys)) {
                $this->data->setRuleUsageItemKey($rule_key, $fit_item_keys);
            } else {
                $this->data->removeRule($rule_key);
            }
        }
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
