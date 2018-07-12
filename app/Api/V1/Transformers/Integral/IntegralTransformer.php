<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\IntegralCategory;
use League\Fractal\TransformerAbstract;

class IntegralTransformer extends TransformerAbstract {

    public function transform(IntegralCategory $integralCategory)
    {
            return [
                'id'                =>  $integralCategory['id'],
                'sort_type'         =>  $integralCategory['sort_type'],
                'title'             =>  $integralCategory['title'],
                'status'            =>  $integralCategory['status'],
                'created_at'        =>  $integralCategory['created_at'],
            ];
    }
}
