<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Preorder;

class PreorderTransformer extends TransformerAbstract
{

    public function transform(Preorder $preorder)
    {
        $data = [
            'name' => $preorder->name,
            'user_id' => $preorder->user_id,
            'phone' => $preorder->phone,
            'address' => $preorder->district,
            'station_id' => $preorder->detail,
            'order_no' => $preorder->order_no,
            'start_time' => $preorder->start_time,
            'end_time' => $preorder->end_time,
            'pause_time' => $preorder->pause_time,
            'restart_time' => $preorder->restart_time,
            'daytime' => $preorder->daytime,
            'status' => $preorder->status,
            'charge_status' => $preorder->charge_status,
            'created_at' => $preorder->created_at,
            'updated_at' => $preorder->updated_at,
        ];

        return $data;
    }

}
