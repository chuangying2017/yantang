<?php namespace App\Repositories\Promotion\Campaign;

use App\Models\Promotion\Campaign;
use App\Repositories\Promotion\PromotionRepositoryAbstract;
use App\Repositories\Promotion\Traits\Detail;

class EloquentCampaignRepository extends PromotionRepositoryAbstract {

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


}
