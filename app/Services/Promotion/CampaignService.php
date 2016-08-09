<?php namespace App\Services\Promotion;

use App\Repositories\Promotion\Campaign\CampaignRepositoryContract;
use App\Services\Promotion\Rule\RuleServiceContract;

class CampaignService extends PromotionServiceAbstract implements PromotionAutoUsing {

    public function __construct(CampaignRepositoryContract $campaignRepo, RuleServiceContract $ruleService)
    {
        parent::__construct($campaignRepo, $ruleService);
    }

    public function autoUsing()
    {
        $this->checkUsable();

        foreach ($this->ruleService->getRules() as $rule_key => $rule) {
            $this->setUsing($rule_key);
        }

        $this->updateItemsRules();

        return $this;
    }

}
