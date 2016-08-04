<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

class SpecifyRuleService implements SpecifyRuleContract {

    /**
     * @var PromotionAbleUserContract
     */
    protected $user;

    /**
     * @var PromotionAbleItemContract
     */
    protected $items;

    /**
     * @var RuleDataContract
     */
    protected $rules;

    /**
     * @param PromotionAbleUserContract $user
     * @return $this
     */
    public function setUser(PromotionAbleUserContract $user)
    {
        $this->user = $user;
    }

    /**
     * @param PromotionAbleItemContract $items
     * @return $this
     */
    public function setItems(PromotionAbleItemContract $items)
    {
        $this->items = $items;
    }

    /**
     * @param RuleDataContract $rules
     * @param $current_rule_key
     * @return $this
     */
    public function setRules(RuleDataContract $rules, $current_rule_key)
    {
        $this->rules = $rules;
        $this->rules->setRule($current_rule_key);
    }

    public function checkUserQualify(PromotionSupportRepositoryContract $promotionRepo)
    {
        // TODO: Implement checkUserQualify() method.
    }

    public function checkRelateItems()
    {
        // TODO: Implement checkRelateItems() method.
    }

    public function checkItemsInRange()
    {
        // TODO: Implement checkItemsInRange() method.
    }

    public function isUsing()
    {
        // TODO: Implement isUsing() method.
    }

    public function isUsable()
    {
        // TODO: Implement isUsable() method.
    }

    public function setBenefit()
    {
        // TODO: Implement setBenefit() method.
    }

    public function setAsUsing()
    {
//        $discount = $this->rules->getDiscount();
//        $calculator = PromotionProtocol::getRuleBenefitCalculator($discount['type']);
//        $benefit_value = $calculator->calAndSet($discount['mode'], $discount['value'], $items, $this->rules->getRelatedItems());
//
//        $this->rules->setUsing();
//
//        $this->rules->setBenefit($benefit_value);
//        if ($this->isCoupon()) {
//            $items->setCouponBenefit($rule_key, $this->rules->getDiscount(), $benefit_value);
//        } else {
//            $items->setCampaignBenefit($rule_key, $this->rules->getDiscount(), $benefit_value);
//        }
//
//        if ($this->rules->getMultiType()) {
//            $this->rules->unsetAllNotUsingUsable();
//        }
//
//        if ($this->rules->getGroup()) {
//            $this->rules->unsetSameGroupUsable();
//        }
    }

    public function setAsNotUsing()
    {
//        $discount = $this->rules->getDiscount();
//        $calculator = PromotionProtocol::getRuleBenefitCalculator($discount['type']);
//        $calculator->rollback($discount['mode'], $discount['value'], $items, $this->rules->getRelatedItems());
//
//        $this->rules->unsetUsing();
//        $this->rules->unsetBenefit();
//
//        if ($this->isCoupon()) {
//            $items->unsetCouponBenefit($rule_key);
//        } else {
//            $items->unsetCampaignBenefit($rule_key);
//        }
//
//        $this->rules->setConflictUsable();
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


}
