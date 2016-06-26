<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Order\OrderAddress;
use League\Fractal\TransformerAbstract;

class OrderAddressTransformer extends TransformerAbstract {

    public function transform(OrderAddress $address)
    {
        return [
            'order' => [
                'id' => $address['order_id']
            ],
            'name' => $address['name'],
            'phone' => $address['phone'],
            'address' => $address['province'] . $address['city'] . $address['district'] . $address['detail'],
            'zip' => $address['zip']
        ];
    }

}
