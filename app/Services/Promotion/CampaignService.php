<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use App\Services\Promotion\Rule\Data\RuleDataContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class CampaignService extends PromotionServiceAbstract {

    public function __construct(CampaignRepositoryContract $campaignRepo, RuleServiceContract $ruleService)
    {
        parent::__construct($campaignRepo, $ruleService);
    }


    public function setItemRelate(PromotionAbleItemContract $items, RuleDataContract $rules)
    {
        return $items->setRelateCampaigns($rules->getAll());
    }

    public function setItemUsable(PromotionAbleItemContract $items, RuleDataContract $rules, $rule_key)
    {
        return $items->setUsableCampaigns($rule_key);
    }

    public function setItemUsing(PromotionAbleItemContract $items, RuleDataContract $rules, $rule_key)
    {
        // TODO: Implement setItemUsing() method.
    }

    public function setItemNotUse(PromotionAbleItemContract $items, RuleDataContract $rules, $rule_key)
    {
        // TODO: Implement setItemNotUse() method.
    }
}
