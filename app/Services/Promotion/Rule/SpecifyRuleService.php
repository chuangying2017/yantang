<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\EloquentTicketRepository;
use App\Services\Promotion\PromotionProtocol;
use App\Services\Promotion\Rule\Benefit\BenefitCalculator;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Rule\Qualification\QualifyChecker;
use App\Services\Promotion\Rule\Usage\GetRelate;
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
        return $this;
    }

    /**
     * @param PromotionAbleItemContract $items
     * @return $this
     */
    public function setItems(PromotionAbleItemContract $items)
    {
        $this->items = $items;
        return $this;
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
        return $this;
    }

    public function checkUserQualify()
    {
        $qualify = $this->rules->getQualification();

        $user_has_qualify = app()->make(QualifyChecker::class)->setQualifyType($qualify['type'])->check($this->user, $qualify['values']);

        $user_has_quota = $this->userHasQuota($qualify['quantity'], $this->rules->getPromotionID(), null);

        if ($user_has_qualify && $user_has_quota) {
            return true;
        }

        $this->rules->unsetRule();
        return false;
    }

    protected function userHasQuota($quantity, $promotion_id, $rule_id)
    {
        if ($quantity > 0) {
            return $quantity > EloquentTicketRepository::getUserPromotionTimes($promotion_id, $this->user->getUserId(), $rule_id);
        }
        return true;
    }

    public function checkRelateItems($unset_if_not_relate = true)
    {
        $rule_items = $this->rules->getItems();

        $relate_item_keys = app()->make(GetRelate::class)->setRelateType($rule_items['type'])->filter($this->items, $rule_items['values']);

        if (empty($relate_item_keys) || is_null($relate_item_keys) || !$relate_item_keys) {
            if ($unset_if_not_relate) {
                $this->rules->unsetRule();
                return false;
            } else {
                $this->rules->setMessage('没有符合优惠的商品');
                return false;
            }
        }

        $this->rules->setRelated($relate_item_keys);

        return true;
    }

    public function checkItemsInRange()
    {
        $usable_item_keys = $this->rules->getRelatedItems();
        $range = $this->rules->getRange();

        $usable_items_range_value = $this->calItemsRange($this->items->getItems($usable_item_keys), $range['type']);

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

        $this->rules->setUsable();

        return true;
    }

    protected function calItemsRange($usable_items, $type)
    {
        $usable_items_range_value = 0;
        if ($type == PromotionProtocol::RANGE_TYPE_OF_AMOUNT) {
            foreach ($usable_items as $item) {
                $usable_items_range_value = bcadd($usable_items_range_value, bcmul($item['quantity'], $item['price']));
            }
        } else if ($type == PromotionProtocol::RANGE_TYPE_OF_QUANTITY) {
            foreach ($usable_items as $item) {
                $usable_items_range_value = bcadd($usable_items_range_value, $item['quantity']);
            }
        }

        return $usable_items_range_value;
    }

    public function isUsing()
    {
        return $this->rules->isUsing();
    }

    public function isUsable()
    {
        return $this->rules->isUsable();
    }

    public function setBenefit()
    {
        $discount = $this->rules->getDiscount();

        $benefit_value = app()->make(BenefitCalculator::class)
            ->setBenefitType($discount['type'])
            ->cal(
                $discount['mode'],
                $discount['value'],
                $this->items,
                $this->items->getItems($this->rules->getRelatedItems())
            );

        $this->rules->setBenefit($benefit_value);

        $this->items->addPromotion($this->rules);

        return $this;
    }

    public function setAsUsing()
    {
        if (!$this->isUsable()) {
            return false;
        }

        $this->rules->setUsing();

        $this->setBenefit();

        if ($this->rules->getMultiType()) {
            $this->rules->unsetOtherUsable();
        }

        if ($this->rules->getGroup()) {
            $this->rules->unsetSameGroupUsable();
        }

        return true;
    }

    public function unsetBenefit()
    {
        $this->items->removePromotion($this->rules);

        return $this;
    }

    public function setAsNotUsing()
    {
        if (!$this->isUsing()) {
            return false;
        }

        $this->unsetBenefit();

        $this->rules->setConflictUsable();

        return true;
    }

    public function isCoupon()
    {
        return $this->rules->getType() == PromotionProtocol::MODEL_OF_COUPON;
    }
}
