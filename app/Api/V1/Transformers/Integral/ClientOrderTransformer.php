<?php

namespace App\Api\V1\Transformers\Integral;

use App\Models\Integral\IntegralOrder;
use App\Repositories\Integral\OrderRule\OrderIntegralProtocol;
use League\Fractal\TransformerAbstract;

class ClientOrderTransformer extends TransformerAbstract {

    public function transform(IntegralOrder $integralOrder)
    {
        $data   =   [
            'order_id'  =>  $integralOrder['id'],
            'order_no'  =>  $integralOrder['order_no'],
            'status'    =>  OrderIntegralProtocol::ORDER_STATUS_ARRAY[$integralOrder['status']],
            'costIntegral'=> $integralOrder['cost_integral'],
            'create_date'=> $integralOrder['created_at']->toDateTimeString(),
        ];

        if ($integralOrder->relationLoaded('integral_order_sku'))
        {
            $express = $integralOrder->integral_order_sku;
            if(empty($express->express))
            {
                $data['expressName'] = '无';
                $data['expressOrder'] = '无';
            }else
            {
                $data['expressName'] = $express->express;
                $data['expressOrder'] = $express->expressOrder;
            }

            $data['product_name'] = $express->product_name;
            if ($express->integral_product)
            {
                $data['image']  =   $express->integral_product()->first()->cover_image;
            }
        }

        return $data;
    }
}
