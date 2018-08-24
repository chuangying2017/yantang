<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\SignMonthModel;
use League\Fractal\TransformerAbstract;

class SignTransformer extends TransformerAbstract {

    public function transform(SignMonthModel $signMonthModel)
    {
        $data = [
            'total' => $signMonthModel['total'],
            'continuousSign'    => $signMonthModel['continuousSign'],
            'days' => $signMonthModel['signArray']
        ];

        if ($signMonthModel->relationLoaded('sign_cte'))
        {
            $data['sign_cte'] = $signMonthModel->sign_cte->sign_integral;
        }

        return $data;
    }
}
