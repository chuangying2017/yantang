<?php namespace App\Api\V1\Transformers;

use App\Models\Client\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract {

    public function transform(Address $address)
    {
        return [
            'id'           => (int)$address->id,
            'name'         => $address->name,
            'mobile'       => $address->mobile,
            'province'     => $address->province,
            'city'         => $address->city,
            'district'     => $address->district,
            'detail'       => $address->detail,
            'tel'          => $address->tel,
            'display_name' => isset($address->display_name) ? $address->display_name : '',
            'address'      => $address->province . $address->city . $address->detail,
        ];
    }
}
