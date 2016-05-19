<?php namespace App\Repositories\Subscribe\Address;

use App\Models\Subscribe\Address;

class EloquentAddressRepository implements AddressRepositoryContract
{

    public function moder()
    {
        return 'App\Models\Subscribe\Address';
    }

    public function create($input)
    {
        $input['province'] = "广东省";
        $input['city'] = "广州市";
        return Address::create($input);
    }

}