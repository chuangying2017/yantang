<?php namespace App\Http\Transformers;

use App\Models\DiscountLimit;
use League\Fractal\TransformerAbstract;

class DiscountLimitTransformer extends TransformerAbstract {

    public function transform(DiscountLimit $discountLimit)
    {
        return [
            'expire_time' => $discountLimit->expire_time
        ];
    }

}
