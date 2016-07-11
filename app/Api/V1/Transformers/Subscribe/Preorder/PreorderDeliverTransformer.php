<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Models\Subscribe\PreorderDeliver;
use League\Fractal\TransformerAbstract;


class PreorderDeliverTransformer extends TransformerAbstract {

    public function transform(PreorderDeliver $deliver)
    {
        $this->defaultIncludes = ['skus'];
        return [
            'id' => $deliver->id,
            'user' => ['id' => $deliver->user_id],
            'station' => ['id' => $deliver->station_id],
            'staff' => ['id' => $deliver->staff_id],
            'deliver_at' => $deliver->deliver_at,
            'status' => $deliver->status,
            'checkout' => $deliver->checkout,
        ];
    }

    public function includeSkus(PreorderDeliver $deliver)
    {
        return $this->collection($deliver->skus, new PreorderDeliverSkuTransformer(), true);
    }

}
