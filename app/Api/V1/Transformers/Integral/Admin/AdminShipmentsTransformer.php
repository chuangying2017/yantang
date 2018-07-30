<?php

namespace App\Api\V1\Transformers\Integral\Admin;

use App\Models\Integral\IntegralOrder;
use League\Fractal\TransformerAbstract;

class AdminShipmentsTransformer extends TransformerAbstract {

   // protected $defaultIncludes = ['user_provider', 'integral_order_sku', 'integral_order_address'];

    public function transform(IntegralOrder $integralOrder)
    {

        $data = [
            'order_no'      =>  $integralOrder['order_no'],
            'postage'       =>  $integralOrder['postage'],
            'pay_channel'   =>  $integralOrder['pay_channel'],
            'status'        =>  $integralOrder['status'],
            'total'         =>  $integralOrder['cost_integral'],//下单总积分
            'create_date'   =>  $integralOrder['updated_at'],//创建时间
        ];

        if ($integralOrder->relationLoaded('integral_order_sku'))
        {
            $data['user_info'] = $this->includeUserProvider($integralOrder);

            $data['orderSku'] = $this->includeIntegralOrderSku($integralOrder);
        }

        if ($integralOrder->relationLoaded('integral_order_address'))
        {
            $data['address'] = $this->includeIntegralOrderAddress($integralOrder);
        }

        return $data;
    }

    public function includeUserProvider(IntegralOrder $integralOrder)
    {
        $user = $integralOrder->user_provider()->first();
        return ['photo' => $user->avatar, 'nickname' => $user->nickname];
    }

    public function includeIntegralOrderSku(IntegralOrder $integralOrder)
    {
        $order_sku = $integralOrder->integral_order_sku;

        return [
            'productName' => $order_sku->product_name,
            'product_image' => $order_sku->integral_product()->first()->cover_image,
        ];
    }

    public function includeIntegralOrderAddress(IntegralOrder $integralOrder)
    {
        return $integralOrder->integral_order_address->toArray();
    }
}
