<?php namespace App\Api\V1\Transformers\Subscribe\Preorder;

use League\Fractal\TransformerAbstract;
use App\Models\Subscribe\Address;

class AddressTransformer extends TransformerAbstract
{

    public function transform(Address $address)
    {
        $data = [
            'address' => $address->address,
            'phone' => $address->phone,
            'district' => $address->district,
            'detail' => $address->detail,
        ];
        
        return $data;
    }

}
