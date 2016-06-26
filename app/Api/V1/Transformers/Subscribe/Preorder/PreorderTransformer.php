<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Preorder;
use App\Services\Subscribe\PreorderProtocol;

class PreorderTransformer extends TransformerAbstract {

    public function transform(Preorder $preorder)
    {
        $data = [
            'id' => $preorder->id,
            'name' => $preorder->name,
            'user_id' => $preorder->user_id,
            'phone' => $preorder->phone,
            'address' => $preorder->address,
            'station_id' => $preorder->station_id,
            'district_id' => $preorder->district_id,
            'order_no' => $preorder->order_no,
            'pause_time' => $preorder->pause_time,
            'restart_time' => $preorder->restart_time,
            'status' => $preorder->status,
            'status_name' => PreorderProtocol::preorderStatusName($preorder->status, $preorder->charge_status),
            'charge_status' => $preorder->charge_status,
            'created_at' => $preorder->created_at,
            'updated_at' => $preorder->updated_at,
        ];

        return $data;
    }

    public function includeSkus(Preorder $preorder)
    {
        return $this->collection($preorder->skus, new PreorderSkuTransformer());
    }

}
