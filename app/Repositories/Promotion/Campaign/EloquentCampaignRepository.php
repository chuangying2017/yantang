<?php namespace App\Repositories\Promotion\Campaign;

use App\Models\Promotion\Campaign;
use App\Models\Promotion\UserPromotion;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Detail;

class EloquentCampaignRepository extends PromotionRepositoryAbstract implements CampaignRepositoryContract {

    use Detail;

    protected function init()
    {
        $this->setModel(Campaign::class);
    }

    protected function attachRelation($promotion_id, $data)
    {
        $this->createDetail($promotion_id, $data['detail']);
    }

    protected function updateRelation($promotion_id, $data)
    {
        return $this->updateDetail($promotion_id, $data['detail']);
    }

    public function get($promotion_id, $with_detail = true)
    {
        $campaign = $promotion_id instanceof Campaign ? $promotion_id : Campaign::find($promotion_id);
        $relation = ['rules'];
        if ($with_detail) {
            $relation[] = ['detail'];
        }
        $campaign = $campaign->load($relation);

        return $campaign;
    }

    public function getUsefulPromotions()
    {
        $campaigns = $this->getAll();
        $campaigns->load('rules', 'counter');

        return $campaigns;
    }
}
