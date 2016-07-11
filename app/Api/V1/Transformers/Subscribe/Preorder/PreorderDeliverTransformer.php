<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Models\Subscribe\PreorderDeliver;
use League\Fractal\TransformerAbstract;


class PreorderDeliverTransformer extends TransformerAbstract {

    public function transform(PreorderDeliver $deliver)
    {
        return [
            'id' => $deliver->id,
            'user' => ['id' => $deliver->user_id],
            'station' => ['id' => $deliver->station_id],
            'staff' => ['id' => $deliver->staff_id],
            'deliver_at' => $deliver->pay_at,
            'status' => $deliver->status,
            'checkout' => $deliver->checkout,
        ];
    }

}
