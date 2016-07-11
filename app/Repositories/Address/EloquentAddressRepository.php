<?php namespace App\Repositories\Address;

use App\Models\Client\Address;
use App\Models\Client\AddressInfo;

class EloquentAddressRepository implements AddressRepositoryContract {

    public function getAddress($address_id)
    {
        if ($address_id instanceof Address) {
            return $address_id;
        }

        return Address::findOrFail($address_id);
    }

    public function getAllAddress()
    {
        return Address::orderBy('index', 'desc')->get();
    }

    public function addAddress($data)
    {
        $address = Address::create(
            array_merge(['user_id' => access()->id()], $this->filterData($data))
        );
        $this->checkIsPrimary($data, $address['id']);
        $address = $this->checkIsSubscribe($data, $address);

        return $address;
    }

    protected function filterData($data)
    {
        return array_only($data, [
            'name',
            'phone',
            'province',
            'city',
            'district',
            'detail',
            'zip',
            'display_name',
            'is_primary'
        ]);
    }

    protected function checkIsPrimary($data, $address_id)
    {
        if (array_get($data, 'is_primary', false)) {
            $this->setPrimaryAddress($address_id);
        }
    }

    protected function checkIsSubscribe($data, $address)
    {
        if (array_get($data, 'station_id', false)) {
            $address['is_subscribe'] = 1;
            $address->save();
            $this->attachAddressInfo($data, $address['id']);
            $address->load('info');

            return $address;
        }
        return $address;
    }

    protected function attachAddressInfo($data, $address_id)
    {
        return AddressInfo::query()->updateOrCreate(
            ['address_id' => $address_id],
            [
                'address_id' => $address_id,
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude'],
                'station_id' => $data['station_id'],
                'district_id' => $data['district_id'],
            ]
        );
    }

    public function updateAddress($address_id, $data)
    {
        $address = Address::query()->where('id', $address_id)->update($this->filterData($data));
        $this->checkIsPrimary($data, $address['id']);
        $address = $this->checkIsSubscribe($data, $address);
        return $address;
    }

    public function deleteAddress($address_id)
    {
        return Address::destroy($address_id);
    }

    public function getPrimaryAddress($strict = false)
    {
        $address = Address::where('is_primary', 1)->first();
        if (!$address && $strict) {
            $address = Address::orderBy('created_at', 'desc')->first();
        }
        return $address;
    }

    public function setPrimaryAddress($address_id)
    {
        $address = $this->getPrimaryAddress(true);
        if ($address) {
            $address->is_primary = 0;
            $address->save();
        }
        Address::query()->where('id', $address_id)->update(['is_primary' => 1]);
    }

    public function getAllSubscribeAddress()
    {
        return Address::with('info')->where('is_subscribe', 1)->get();
    }
}
