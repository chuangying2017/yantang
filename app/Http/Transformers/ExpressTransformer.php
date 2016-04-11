<?php namespace App\Http\Transformers;

use App\Models\Address;
use App\Models\OrderDeliver;
use League\Fractal\TransformerAbstract;

class ExpressTransformer extends TransformerAbstract {

    public function transform(OrderDeliver $deliver)
    {
        return [
            'company_name' => $deliver->company_name,
            'post_no'      => $deliver->post_no,
            'status'       => $deliver->status,
            'created_at'   => $deliver->created_at->toDatetimeString(),
        ];
    }
}
