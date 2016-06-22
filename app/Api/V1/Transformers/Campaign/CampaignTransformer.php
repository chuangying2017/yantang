<?php namespace App\Api\V1\Transformers\Campaign;

use App\Models\Promotion\Campaign;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract {

    public function transform(Campaign $campaign)
    {
        $data = [
            'id' => $campaign['id'],
            'name' => $campaign['name'],
            'desc' => $campaign['desc'],
            'start_at' => $campaign['start_at'],
            'end_at' => $campaign['end_at'],
        ];
        if (isset($data['detail'])) {
            $data['detail'] = $data['detail']['detail'];
        }

        return $data;
    }
}
