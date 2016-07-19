<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use App\Models\Subscribe\PreorderDeliver;
use League\Fractal\TransformerAbstract;


class PreorderDeliverTransformer extends TransformerAbstract {

    public function transform(PreorderDeliver $deliver)
    {
        $this->defaultIncludes = ['skus'];
        $data = [
            'id' => $deliver->id,
            'user' => ['id' => $deliver->user_id],
            'station' => ['id' => $deliver->station_id],
            'staff' => ['id' => $deliver->staff_id],
            'deliver_at' => substr($deliver->deliver_at, 0, 10),
            'status' => $deliver->status,
            'checkout' => $deliver->checkout,
            'statement_no' => $deliver->statement_no,
        ];

        if ($deliver->relationLoaded('preorder')) {
            $data['name'] = $deliver['preorder']['name'];
            $data['phone'] = $deliver['preorder']['phone'];
        }
        
        return $data;
    }

    public function includeSkus(PreorderDeliver $deliver)
    {
        return $this->collection($deliver->skus, new PreorderDeliverSkuTransformer(), true);
    }


}
