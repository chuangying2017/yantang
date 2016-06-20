<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;
use App\Services\Promotion\Support\PromotionAbleItemContract;

class CampaignService extends PromotionServiceAbstract implements PromotionAutoUsing {

    public function __construct(CampaignRepositoryContract $campaignRepo, RuleServiceContract $ruleService)
    {
        parent::__construct($campaignRepo, $ruleService);
    }

    public function autoUsing(PromotionAbleItemContract $items)
    {
        $this->usable($items);

        foreach ($this->ruleService->getRules()->getAll() as $rule_key => $rule) {
            $this->using($items, $rule_key);
        }

        return $items;
    }
}
