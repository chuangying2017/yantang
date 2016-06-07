<?php namespace App\Api\V1\Transformers\Mall;

use App\Models\Client\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract {

    public function transform(Address $address)
    {
        return [
            'id' => $address['id'],
            'name' => $address['name'],
            'user' => ['id' => $address['user_id']],
            'phone' => $address['phone'],
            'province' => $address['province'],
            'city' => $address['city'],
            'district' => $address['district'],
            'detail' => $address['detail'],
            'display_name' => $address['display_name'],
            'zip' => $address['zip'],
            'index' => $address['index'],
            'is_primary' => $address['is_primary']
        ];
    }
}
