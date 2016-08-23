<?php namespace App\Repositories\Promotion\Campaign;

use App\Models\Promotion\Campaign;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Detail;
use App\Repositories\Promotion\Traits\Skus;

class EloquentCampaignRepository extends PromotionRepositoryAbstract implements CampaignRepositoryContract {

    use Detail, Skus;

    protected function init()
    {
        $this->setModel(Campaign::class);
    }

    protected function attachRelation($promotion_id, $data)
    {
        $this->createSku($promotion_id, $data['product_sku']);
        $this->createDetail($promotion_id, $data['detail']);
    }

    protected function updateRelation($promotion_id, $data)
    {
        $this->updateSku($promotion_id, $data['product_sku']);
        $this->updateDetail($promotion_id, $data['detail']);
    }

    public function get($promotion_id, $with_detail = true)
    {
        $campaign = $promotion_id instanceof Campaign ? $promotion_id : Campaign::query()->findOrFail($promotion_id);
        if ($with_detail) {
            $campaign->load(['detail', 'skus.sku']);
        }

        return $campaign;
    }

    public function getUsefulPromotions()
    {
        $campaigns = $this->getAll();
        $campaigns->load('rules', 'counter');

        return $campaigns;
    }
}
