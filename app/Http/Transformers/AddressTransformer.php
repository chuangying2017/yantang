<?php namespace App\Http\Transformers;

use App\Models\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract {

    public function transform(Address $address)
    {
        return [
            'id'         => $address->id,
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
