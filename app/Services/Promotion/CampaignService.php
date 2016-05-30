<?php namespace App\Services\Promotion;

class CampaignService extends PromotionServiceAbstract {


    public function check($items, $user)
    {
        $campaigns = $this->promotionSupportRepo->getCampaigns();

        foreach ($campaigns as $campaign_key => $campaign) {
            if (!$this->dispatch($campaign, $user['id'])) {
            }
        }
    }

    public function used($promotions, $items, $user, $order)
    {
        // TODO: Implement used() method.
    }

    public function dispatch($campaign, $user)
    {
        foreach ($campaign['rules'] as $rule_key => $rule) {
            if (!$this->canDispatched($user, $rule, $campaign['id'])) {
                unset($campaign['rules'][$rule_key]);
            }
        }

        return count($campaign['rules']);
    }
}
