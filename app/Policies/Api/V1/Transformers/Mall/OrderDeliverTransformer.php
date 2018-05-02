<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Order\OrderDeliver;
use League\Fractal\TransformerAbstract;

class OrderDeliverTransformer extends TransformerAbstract {

    public function transform(OrderDeliver $deliver)
    {
        return [
            'company' => [
                'name' => $deliver['company_name']
            ],
            'post_no' => $deliver['post_no'],
            'status' => $deliver['status']
        ];
    }

}
