<?php namespace App\Services\Promotion\Rule;

use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Traits\RuleMessage;

class RuleService implements RuleServiceContract {

    use RuleMessage;

    private $rule = null;

    protected function userHasQuota($user_id, $quantity)
    {
        if ($quantity > 0) {
            //检查用户参加次数,小于限制次数
            #TODO
        }
        return true;
    }

    public function canGetPromotion($user, $rule_qualify)
    {
        $qualification = PromotionProtocol::getRuleQualification($rule_qualify['type']);

        if (!$qualification->check($user, $rule_qualify['values'])) {
            $this->setMessage('不符合条件,参与失败');
            return false;
        }
        if (!$this->userHasQuota($user['id'], $rule_qualify['quantity'])) {
            $this->setMessage('参加次数超过上限');
            return false;
        }

        return false;
    }

    public function itemsFitPromotion($items, $rule_items)
    {
        $usage = PromotionProtocol::getRuleUsage($rule_items['type']);

        return $usage->filter($items, $rule_items['values']);
    }


    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function calculatePromotionBenefits($items, $discount)
    {
        $benefit_handler = PromotionProtocol::getRuleBenefit($discount['type']);

        return $benefit_handler->calculate($items, $discount['mode'], $discount['value']);
    }


    public function itemsInRange($usable_items, $range)
    {
        $usable_items_range_value = $this->getItemsRangeValue($usable_items, $range['type']);

        if ($range['max']) {
            if ($usable_items_range_value > $range['max']) {
                $this->setMessage('超出限制,无法使用');
                return false;
            }
        }

        if ($usable_items_range_value < $range['min']) {
            $this->setMessage('数量金额不足');
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

}
