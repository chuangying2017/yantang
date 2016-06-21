<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class RuleService implements RuleServiceContract {


    /**
     * @var RuleDataContract
     */
    private $rules;
    /**
     * @var PromotionAbleUserContract
     */
    private $user;

    protected $rule_key;


    /**
     * RuleService constructor.
     * @param RuleDataContract $rules
     */
    public function __construct(RuleDataContract $rules, PromotionAbleUserContract $user)
    {
        $this->rules = $rules;
        $this->user = $user;
    }

    /*
     * @var PromotionSupportRepositoryContract
     */
    protected $promotionSupport;


    public function setRule($rule_key)
    {
        $this->rule_key = $rule_key;
    }

    public function calculatePromotionBenefits($items, $discount)
    {
        $benefit_handler = PromotionProtocol::getRuleBenefitCalculator($discount['type']);

        return $benefit_handler->calAndSet($items, $discount['mode'], $discount['value']);
    }


    public function itemsInRange($usable_items, $range)
    {
        $usable_items_range_value = $this->getItemsRangeValue($usable_items, $range['type']);

        if ($range['max']) {
            if ($usable_items_range_value > $range['max']) {
                $this->rules->setMessage('超出限制,无法使用');
                return false;
            }
        }

        if ($usable_items_range_value < $range['min']) {
            $this->rules->setMessage('数量金额不足');
            return false;
        }

        return true;
    }

    protected function getItemsRangeValue($items, $range_type)
    {
        $item_value = 0;
        foreach ($items as $item) {
            if ($range_type == PromotionProtocol::RANGE_TYPE_OF_AMOUNT) {
                $item_value = bcadd($item_value, bcmul($item['quantity'], $item['price']));
            } else if ($range_type == PromotionProtocol::RANGE_TYPE_OF_QUANTITY) {
                $item_value = bcadd($item_value, $item['quantity']);
            }
        }

        return $item_value;
    }

    /**
     * @param mixed $rules
     * @return RuleService
     */
    public function setRules($rules)
    {
        $this->rules->init($rules);
        return $this;
    }

    protected function userHasQuota($quantity, $promotion_id, $rule_id, PromotionSupportRepositoryContract $promotionSupport)
    {
        if ($quantity > 0) {
            return $quantity > $promotionSupport->getUserPromotionTimes($promotion_id, $this->user->getUserId(), $rule_id);
        }
        return true;
    }

    public function hasQualify($rule_key, PromotionSupportRepositoryContract $promotionSupport)
    {
        $this->rules->setRuleKey($rule_key);
        $rule_qualify = $this->rules->getQualification();

        $qualify_checker = PromotionProtocol::getRuleQualifyChecker($rule_qualify['type']);

        if (!$qualify_checker->check($this->user, $rule_qualify['values'])) {
            $this->rules->setMessage('不符合条件,参与失败');
            return false;
        }

        if (!$this->userHasQuota($rule_qualify['quantity'], $this->rules->getPromotionID(), $this->rules->getRuleID(), $promotionSupport)) {
            $this->rules->setMessage('参加次数超过上限');
            return false;
        }

        return true;
    }

    public function filterRelate(PromotionAbleItemContract $items, PromotionSupportRepositoryContract $promotionSupport)
    {
        foreach ($this->rules->getAll() as $rule_key => $rule) {
            if (!$this->hasQualify($rule_key, $promotionSupport)) {
                $this->rules->unsetRule($rule_key);
            } else {
                $rule_items = $this->rules->setRuleKey($rule_key)->getItems();
                $usage_filter = PromotionProtocol::getRuleUsageFilter($rule_items['type']);
                $this->rules->setRelated($usage_filter->filter($items, $rule_items['values']));
            }
        }

        if ($this->isCoupon()) {
            $items->setRelateCoupons($this->rules->getAll());
        } else {
            $items->setRelateCampaigns($this->rules->getAll());
        }

        return $this;
    }

    public function filterUsable(PromotionAbleItemContract $items)
    {
        /**
         * 规则排序:
         * 1. 排他优先
         * 2. 分组和组内权重降序排序（分组优先,权重大优先）
         * 3. 规则对象范围小优先
         */
        $this->rules->sortRules();

        /**
         * 计算生效的规则
         * 1. 计算优惠资源
         * 4. 记录未生效信息
         */
        foreach ($this->rules->getAll() as $rule_key => $rule) {
            $this->rules->setRuleKey($rule_key);

            if ($this->itemsInRange($this->rules->getRelatedItems(), $this->rules->getRange())) {
                if ($this->isCoupon()) {
                    $items->setUsableCoupons($rule_key);
                } else {
                    $items->setUsableCampaigns($rule_key);
                }
            }
        }

        return $this;
    }

    public function using(PromotionAbleItemContract $items, $rule_key)
    {
        $this->rules->setRuleKey($rule_key);

        /**
         * 1. 计算优惠资源
         * 2. 排他生效后结束
         * 3. 组内权重高生效后跳过同组
         */
        if (!$this->rules->isUsable() || $this->rules->isUsing()) {
            return $this;
        }

        $discount = $this->rules->getDiscount();
        $calculator = PromotionProtocol::getRuleBenefitCalculator($discount['type']);
        $benefit_value = $calculator->calAndSet($discount['mode'], $discount['value'], $items, $this->rules->getRelatedItems());

        $this->rules->setUsing();

        $this->rules->setBenefit($benefit_value);
        if ($this->isCoupon()) {
            $items->setCouponBenefit($rule_key, $this->rules->getDiscount(), $benefit_value);
        } else {
            $items->setCampaignBenefit($rule_key, $this->rules->getDiscount(), $benefit_value);
        }

        if ($this->rules->getMultiType()) {
            $this->rules->unsetAllNotUsingUsable();
        }

        if ($this->rules->getGroup()) {
            $this->rules->unsetSameGroupUsable();
        }

        return $this;
    }

    public function notUsing(PromotionAbleItemContract $items, $rule_key)
    {
        $this->rules->setRuleKey($rule_key);

        if (!$this->rules->isUsing()) {
            return $this;
        }

        $discount = $this->rules->getDiscount();
        $calculator = PromotionProtocol::getRuleBenefitCalculator($discount['type']);
        $calculator->rollback($discount['mode'], $discount['value'], $items, $this->rules->getRelatedItems());

        $this->rules->unsetUsing();
        $this->rules->unsetBenefit();

        if ($this->isCoupon()) {
            $items->unsetCouponBenefit($rule_key);
        } else {
            $items->unsetCampaignBenefit($rule_key);
        }

        $this->rules->setConflictUsable();

        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return $this
     */
    private function isCoupon()
    {
        return $this->rules->getType() == PromotionProtocol::RULE_TYPE_OF_COUPON;
    }
}
