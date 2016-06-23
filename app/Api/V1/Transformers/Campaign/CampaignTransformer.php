<?php namespace App\Api\V1\Transformers\Campaign;

use App\Models\Promotion\Campaign;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract {

    public function transform(Campaign $campaign)
    {
        $data = [
            'id' => $campaign['id'],
            'name' => $campaign['name'],
            'cover_image' => $campaign['cover_image'],
            'desc' => $campaign['desc'],
            'start_at' => $campaign['start_at'],
            'end_at' => $campaign['end_at'],
        ];

        if (isset($campaign['detail'])) {
            $data['detail'] = $campaign['detail']['detail'];
        }

        if (isset($campaign['skus'])) {
            $data['product_sku'] = $campaign['skus']['product_sku_id'];
        }

        return $data;
    }
}
