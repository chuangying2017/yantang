<?php namespace App\Repositories\Promotion;

use Cache;

class CachePromotionSupportRepository implements PromotionSupportRepositoryContract {

    const CACHE_KEY_OF_CAMPAIGNS = 'YT_CURRENT_CAMPAIGNS';


    public function getCampaigns()
    {
        if (Cache::has(self::CACHE_KEY_OF_CAMPAIGNS)) {
            return Cache::get(self::CACHE_KEY_OF_CAMPAIGNS);
        }

        return $this->refreshCampaigns();
    }

    public function getCampaign($campaign_id)
    {
        if (is_int($campaign_id)) {
            $campaigns = $this->getCampaigns();
            return array_get($campaigns, $campaign_id, $this->campaignRepo->get($campaign_id, false)->load('rules', 'counter'));
        }

        return $campaign_id;
    }

    protected function refreshCampaigns()
    {
        $campaigns = $this->campaignRepo->getAll(true);
        $campaigns->load('rules', 'counter');

        Cache::forget(self::CACHE_KEY_OF_CAMPAIGNS);

        return Cache::remember(self::CACHE_KEY_OF_CAMPAIGNS, $this->getCacheTime($campaigns), function () use ($campaigns) {
            return $this->decodePromotions($campaigns);
        });
    }

    private function getCacheTime($promotions)
    {
        $recent_end_time = null;
        foreach ($promotions as $promotion) {
            if (is_null($recent_end_time) || strtotime($promotion['end_time']) < $recent_end_time) {
                $recent_end_time = strtotime($promotion['end_time']);
            }
        }
        return bcdiv($recent_end_time - time(), 60, 0);
    }


    public function getUsefulRules()
    {
        // TODO: Implement getUsefulRules() method.
    }

    public function getUserPromotionTimes($promotion_id, $user_id, $rule_id = null)
    {
        // TODO: Implement getUserPromotionTimes() method.
    }
}
