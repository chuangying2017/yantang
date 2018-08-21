<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\SignMonthModel;
use League\Fractal\TransformerAbstract;

class SignTransformer extends TransformerAbstract {

    public function transform(SignMonthModel $signMonthModel)
    {
        $data = [
            'total' => $signMonthModel->total,
            'continuousSign'    => $signMonthModel->continuousSign,
        ];

        if ($signMonthModel->relationLoaded('sign_integral_record'))
        {
            $arr = [];

            foreach ($signMonthModel->sign_integral_record as $key => $item)
            {
                $arr[$key] = ['days' => $item['days']];
            }

            $data['signDay'] = $arr;
        }

        return $data;
    }
}
