<?php namespace App\Api\V1\Transformers\Subscribe\Station;

use App\Models\Subscribe\Preorder;
use League\Fractal\TransformerAbstract;

class StaffPreorderTransformer extends TransformerAbstract {

    public function transform(Preorder $order)
    {
        $data = [

        ];

        return $data;
    }

}
