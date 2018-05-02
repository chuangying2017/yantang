<?php namespace App\Api\V1\Transformers\Campaign;

use App\Models\Order\OrderSpecialCampaign;
use League\Fractal\TransformerAbstract;

class OrderSpecialCampaignTransformer extends TransformerAbstract {

    /**
     * OrderSpecialCampaignTransformer constructor.
     */
    public function transform(OrderSpecialCampaign $campaign)
    {
        return [
            'id' => $campaign['campaign_id'],
            'name' => $campaign['campaign_name'],
            'cover_image' => $campaign['campaign_cover_image'],
        ];
    }
}
