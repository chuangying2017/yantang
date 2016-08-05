<?php namespace App\Services\Promotion\Rule;

use App\Repositories\Promotion\PromotionSupportRepositoryContract;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;
use App\Services\Promotion\Support\PromotionAbleUserContract;

interface SpecifyRuleContract {

    //初始设置
    /**
     * @param PromotionAbleUserContract $user
     * @return $this
     */
    public function setUser(PromotionAbleUserContract $user);

    /**
     * @param PromotionAbleItemContract $items
     * @return $this
     */
    public function setItems(PromotionAbleItemContract $items);

    /**
     * @param RuleDataContract $rules
     * @param $current_rule_key
     * @return $this
     */
    public function setRules(RuleDataContract $rules, $current_rule_key);

    //检查计算
    //unset if don't has qualify
    public function checkUserQualify();

    public function checkRelateItems();

    public function checkItemsInRange();

    //判断
    public function isUsing();

    public function isUsable();

    //操作
    public function setBenefit();

    public function unsetBenefit();

    public function setAsUsing();

    public function setAsNotUsing();


}
